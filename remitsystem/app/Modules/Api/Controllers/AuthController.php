<?php

namespace App\Modules\Api\Controllers;

use App\Http\Requests\Auth\ResendActivationCodeRequest;
use App\Modules\Application\Models\Application;
use App\Modules\Application\Service\GetApplicationService;
use App\Modules\Auth\Service\SendSenderRegisteredEmailService;
use App\Modules\Referral\Models\Referral;
use App\Modules\Sender\Models\Document;
use App\Modules\Sender\Models\Identification;
use App\Modules\Sender\Models\IdentificationDocument;
use App\Modules\Sender\Models\Sender;
use App\Modules\Sender\Service\GetSenderDetailsService;
use App\Modules\Transaction\Models\Transaction;
use App\Modules\Transaction\Service\Mail\OtpMailService;
use App\Modules\Transaction\Service\Otp\GenerateOtpService;
use App\Modules\Transaction\Service\Otp\VerifyOtpService;
use App\Modules\User\Models\Address;
use App\Modules\User\Models\Person;
use App\Modules\User\Models\PersonAddress;
use App\Modules\User\Models\PersonPhone;
use App\Modules\User\Models\Phone;
use App\Modules\User\Models\User;
use App\Notifications\IdentificationRequestNotification;
use App\Notifications\PasswordResetRequest;
use App\Otp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuthController extends ApiController
{
    private $generateOtpService;
    private $otpMailService;
    protected $sendOtpMailService;
    private $verifyOtpService;
    private $transaction;

    function __construct(
        Transaction              $transaction,
        GenerateOtpService       $generateOtpService,
        OtpMailService           $otpMailService,
        VerifyOtpService         $verifyOtpService,
        OtpMailService           $sendOtpMailService,
        private SendSenderRegisteredEmailService $sendSenderRegisteredEmailService,
    )
    {
        $this->verifyOtpService = $verifyOtpService;
        $this->otpMailService = $otpMailService;
        $this->generateOtpService = $generateOtpService;
        $this->sendOtpMailService = $sendOtpMailService;
        $this->transaction = $transaction;
    }

    public function Login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|string|email|max:191',
            'password' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['response' => $validator->errors(), 'message'=>'Invalid entry.','status'=>422]);
        }

        $application = getAppDetails();
        $user = User::where('email', '=', $request->email)->where('level_id',5)->where('application_id',$application->id)->first();

        if(!$user){
            return response()->json(['message' => 'You are not registered in our system. Please proceed to registration. Thank you', 'status' => 404]);
        }

        if($user->api_token === ''){
            return response()->json(['message' => 'There is problem with your account, please contact admin ('.$application->phone_number.')', 'status' => 404]);
        }
        if ((int)$user->user_status_id === 2) {
            if (auth()->attempt(['email' => $request->email, 'password' => $request->password,'application_id'=>$application->id]) === false) {
                return response()->json(['message' => 'Invalid Password', 'status' => 400]);
            } else {
                if(isset($request->firebase_token)){
                    $user->firebase_token = $request->firebase_token;
                    $user->save();
                }
                $user_model = new User();
                $user_details = $user_model->getDetails($user->id);
                $transaction_count = $this->getTransaction()->where('transactions.added_by', $user->id)->count();
                $successful_transaction = $this->getTransaction()->where('transactions.added_by', $user->id)->where('transactions.transaction_status_id',10)->count();
                $user_details->total_transaction_count = $transaction_count;
                $user_details->successful_transaction_count = $successful_transaction;
                if($user_details){
                    $user_details->image =  url('/') . '/identification/' . $user_details->image;
                    $user_details->image1 =  url('/') . '/identification/' . $user_details->image1;
                }
                return response()->json(['response' => $user_details, 'message' => 'Success', 'status' => 200]);
            }
        } else {
            $newAuthCode = substr(str_shuffle("0123456789"), 0, 6);
            $user->auth_code = $newAuthCode;
            $user->save();

            $userData = $user->refresh();
            $this->sendActivationEmail($userData, $userData->person_id,$application);
            return response()->json(['message' => 'Your account has not been activated yet.An activation link has been sent to your email.Please enter the code sent to your email to activate your account.For any query contact the administrator : '.$application->phone_number.' or wait until your account got approved by support team.',
                'status' => 401]);
        }
    }

    public function verifyEmail(Request $request)
    {
        if ($request) {
            $validator = Validator::make($request->all(),[
                'email' => 'required|string|email|max:191',
                'verification_code' => 'required'
            ]);
            if($validator->fails()){
                return response()->json(['response'=>$validator->errors(),'message'=>'Invalid entry.','status'=>422]);
            }
            $user = User::where('email', '=', $request->email)->where('level_id',5)->first();

            if ($user == null) {
                return response()->json(['message' => 'User doesnot exist', 'status' => 404]);
            } else {
                if($request->verification_code){
                    if ($user->auth_code == $request->verification_code) {
                        $user->user_status_id = 2;
                        $user->active = 1;
                        $user->auth_code = '';
                        $user->email_verified_at = date('Y-m-d H:i:s');
                        $user->save();
                        $user_model = new User();
                        $user_details = $user_model->getDetails($user->id);
                        $user_details->image =  url('/') . '/identification/' . $user_details->image;
                        $user_details->image1 =  url('/') . '/identification/' . $user_details->image1;
                        return response()->json(['response' => $user_details, 'message' => 'Your email has been verified. You will receive an email once we approve the documents. Thank you.', 'status' => 200]);
                    } else {
                        return response()->json(['message' => 'Verification Code does not match', 'status' => 404]);
                    }
                } else{
                    return response()->json(['message' => 'Verification Code not found', 'status' => 404]);
                }
            }
        }
        return response()->json(['message' => 'Failed', 'status' => 404]);
    }

    public function Register(Request $request)
    {
        if ($request) {
            /*$validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:191|unique:users',
            ]);*/
            $application = getAppDetails();
            $old_user = User::where('email', $request->email)->where('application_id', $application->id)->first();
            if (!empty($old_user)) {
                //if($validator->errors()->first('email') == 'The email has already been taken.'){
                    return response()->json(['response'=>[], 'message'=>'You have already registered. Please contact admin ('.$application->phone_number.') or go to forgot password.','status'=>422]);
                //}
                //return response()->json(['response'=>$validator->errors(), 'message'=>'Invalid entry.','status'=>422]);
            } else {
                $validator = Validator::make($request->all(), [
                    'password' => 'required|string|min:6|confirmed',
                    'first_name' => 'required|min:2|max:55',
                    'last_name' => 'required|min:2|max:55',
                    'phone_number' => ['required','regex:/^(?:\+?61|0)4([0-9]{8})$/'],
                    'dob' => 'required',
                    'issued_by' => 'required',
                    'id_number' => 'required',
                    'id_type' => 'required',
                    'expiry_date' => 'required',
                    'image' => 'required |max:10000',
                    'image1' => 'max:10000',
                    'address_proof' => 'required |max:10000',
                    'street' => 'required',
                    'suburb' => 'required',
                    'postcode' => 'required',
                    'state' => 'required',
                    'country_list_id' => 'required',
                    'otp' => 'required',
                ]);
                if ($validator->fails()) {
                    $validationErrorMessage = implode(",",$validator->messages()->all());
                    $validationErrorMessage .= '. If any issue contact admin ('.$application->phone_number.')';
                    return response()->json(['response'=>$validator->errors(), 'message'=>$validationErrorMessage,'status'=>422]);
                } else {
                    if(isset($request->referral_code)){
                    $validation = validateReferralCode($request->referral_code,$application->id);
                        if($validation != 'true'){
                            $errorMsg = 'The referral code is invalid';
                            return response()->json(['response'=>$validation, 'message'=>$errorMsg,'status'=>422]);
                        }
                    }


                    DB::beginTransaction();
                    try {
                        $otp = $request->get('otp');
                        $otpObject = Otp::where('otp',$otp)
                            ->where('email',$request->email)
                            ->where('status',1)
                            ->latest()
                            ->first();
                        if(!$otpObject){
                            return response()->json(['message' => 'Invalid otp', 'status' => 404]);
                        }
                        $otpObject->delete();

                        $person = Person::create([
                            'first_name' => $request->first_name,
                            'last_name' => $request->last_name,
                            'dob' => insert_dateformat($request->dob),
                            'email' => strtolower($request->email),
                        ]);
                        $user_model = new User();
                        if ($user_model->validate($request->all(),$application->application_id))
                        {
                            $users = User::create([
                                'email' => strtolower($request->email),
                                'password' => Hash::make($request->password),
                                'person_id' => $person->id,
                                'level_id' => 5,
                                'user_status_id' => 1,
                                'active' => 0,
                                'api_token' => $this->getApiToken(),
                                'application_id' => $application->id,
                                'referral_code' => generateReferralCode($application->id)
                            ]);
                            $users->auth_code = substr(str_shuffle("0123456789"), 0, 6);
                            $users->save();
                        }
                        else
                        {
                            DB::rollback();
                            $errors = $user_model->errors();
                            return response()->json(['response'=>[], 'message'=>$errors,'status'=>422]);

                        }

                        $phone = new Phone();
                        $phone->number = $request->phone_number;
                        $phone->save();
                        PersonPhone::create([
                            'phones_id' => $phone->id,
                            'person_id' => $person->id,
                            'current' => 1
                        ]);

                        $address = Address::create([
                            'street' => $request->street,
                            'suburb' => $request->suburb,
                            'postcode' => $request->postcode,
                            'state' => $request->state,
                            'country_list_id' => $request->country_list_id,
                        ]);

                        PersonAddress::create([
                            'address_id' => $address->id,
                            'person_id' => $person->id,
                            'current' => 1,
                            'address_status_id' => 1
                        ]);

                        // FOR NEPAL PAISA PP
                        $addedby = $application->agent_id;
                        $sender_model = new Sender();
                        if ($sender_model->validate($request->all(),$application->agent_id))
                        {
                            $sender = Sender::create([
                                'person_id' => $person->id,
                                'added_by' => $addedby,
                                'sender_status_id' => 1
                            ]);
                        }
                        else
                        {
                            DB::rollback();
                            $errors = $sender_model->errors();
                            return response()->json(['response'=>[], 'message'=>$errors,'status'=>422]);
                        }

                        $sender_model->checkSimilarity($request->all(),$sender->id,$users->application_id);

                        $identification_document_id = $this->uploadIdentification($request->image, $person->id, $users->id, $request->image1,$request['address_proof'] ?? null);
                        Identification::create([
                            'issued_by' => $request->issued_by,
                            'id_number' => $request->id_number,
                            'identification_status_id' => 2,
                            'identification_types_id' => $request->id_type,
                            'expiry_date' => insert_dateformat($request->expiry_date),
                            'senders_id' => $sender->id,
                            'identification_documents_id' => $identification_document_id,
                            'current' => 1
                        ]);
                        if(isset($request->referral_code)) {
                            if (!empty($request->referral_code)) {
                                $referral = new Referral();
                                $referral->add($users->id, $request->referral_code, $application->discount_percent);
                            }
                        }
                        /*$sms = new sms();
                        $sms->sendActivationSms($users,$person->id); */
                        /**
                         * mail
                         */

                        DB::commit();
                        $this->sendActivationEmail($users, $person->id,$application);
                        try{
                            $this->sendSenderRegisteredEmailService->sendToAdmin($sender);
                        }catch (\Throwable $e){
                            Log::critical('Sender registered email service failed to send to admin.');
                            Log::critical($e->getMessage());
                            Log::critical($e->getTraceAsString());
                        }
                    } catch (\Throwable $e) {
                        DB::rollback();
                        \Log::error('Registration error: '. $e->getMessage());
                        \Log::error('Registration error: '. $e->getTraceAsString());
                        $message = 'Sorry Client could not be saved.';

                        if (env('APP_DEBUG'))
                            $message = $e->getMessage() . 'Line #' . $e->getLine();
                        return response()->json(['message' => $e->getMessage(), 'status' => 404]);

                    }
                    $responseMsg = 'Your Account has been created successfully. The verification code has been sent to your inbox. Please enter the verification code sent to your email. Please check junk folder for email as well.';
                    $responseMsg = 'Thank you very much for registering with us. Please wait for admin approval or contact us on '.$application->phone_number;
                    return response()->json(['message' => $responseMsg, 'status' => 200]);
                }
            }

        }
        return response()->json(['message' => 'Failed', 'status' => 404]);
    }

    public function sendActivationEmail($userData, $person_id, $application)
    {
        $userName = Person::where('id', $person_id)->first();

        $url = route('activate', $userData['auth_code']);
        $email_template = getEmailTemplate('type','verification',$application);

        if($email_template){
            $subject = $email_template->subject;
            $body = $email_template->message;
            $data_array_parse = array(
                'FULL_NAME'  => $userName['first_name'] . ' ' . $userName['last_name'],
                'VERIFICATION_CODE'  => $userData['auth_code'],
            );
            $data_array_parse = format_template_array($application,$data_array_parse);
            $subject = parseTemplate($subject,$data_array_parse);
            $body = parseTemplate($body,$data_array_parse);
            $view = 'EmailTemplate::Email/email';
        }
        else{
            $subject = 'Verify your email';
            $body = 'Thank you for Registration. Your activation code is ' . $userData['auth_code'] . '';
            $view = 'Auth::Email/email_verify_activation';
        }

        $param = [
            'to' => strtolower($userData['email']),
            'toName' => $userName['first_name'] . ' ' . $userName['last_name'],
            'body' => $body,
            'subject' => $subject,
            'fromEmail' => $application->email,
            'fromName' => $application->name
        ];

        Mail::send($view, $param, function ($message) use ($param) {
            $message->to($param['to'], $param['toName'])
                ->from($param['fromEmail'], $param['fromName'])
                ->subject($param['subject']);
        });
    }

    public function uploadIdentification($image, $id = 0, $user_id, $image1 = null,$address_proof = null)
    {
        $file = $image;
        $destinationPath = 'identification';
        $identityDoc = [];
        if (\Illuminate\Support\Facades\Request::hasfile('image')) {
            $fileName = date('Y-m-d-H-i-s') . $file->getClientOriginalName();
            $file->move($destinationPath, $fileName);
            $document = Document::create([
                'type' => '',
                'user_id' => $user_id,
                'name' => $fileName,
                'shelf_location' => '',
            ]);

            if(isset($image1) && \Illuminate\Support\Facades\Request::hasfile('image1')){
                $file1 = $image1;
                $fileName1 = date('Y-m-d-H-i-s').$image1->getClientOriginalName();
                $file1->move($destinationPath, $fileName1);

                $document->name1 = $fileName1;
                $document->save();
            }
            if (isset($address_proof) && \Illuminate\Support\Facades\Request::hasfile('address_proof')) {
                $file2 = $address_proof;
                $fileName2 = date('Y-m-d-H-i-s') . $address_proof->getClientOriginalName();
                $file2->move($destinationPath, $fileName2);

                $document->address_proof = $fileName2;
                $document->save();
            }
            $identityDoc['document_id'] = $document->id;
        }

        if (\Illuminate\Support\Facades\Request::hasfile('image') || \Illuminate\Support\Facades\Request::hasfile('image1')) {
            $identification_document = IdentificationDocument::create($identityDoc);
            return ($identification_document->id);
        }
    }

    public function sendResetLinkEmail(Request $request){
        if($request){
            $validator = Validator::make($request->all(),[
               'email' => 'required|string|email|max:191'
            ]);

            if($validator->fails()){
                return response()->json(['response' => $validator->errors(),'message'=>'Invalid entry.','status'=>422]);
            }
            $user = User::where('users.email', '=', $request->email)->where('level_id',5)->first();
            if($user == null){
                return response()->json(['message' => 'The email doesn’t exist in our database. Please register to send money to Nepal easily.', 'status' => 404]);
            } else {
                $token = str_random(60);
                $passwordReset = DB::table('password_resets')->updateOrInsert(
                    ['email' => $user->email],
                    [
                        'email' => $user->email,
                        'token' => $token
                    ]
                );
                if ($user && $passwordReset)
                    $application = Application::where('package_name',$request->header('X-APP-PKG'))->first();
                    try {
                        $user->notify(
                            new PasswordResetRequest($token,$application)
                        );
                    } catch (\Exception $e) {
                        Log::error($e);
                    }
                return response()->json([
                    'message' => 'The verification code has been sent to your email address. Please do check your SPAM folder if incase you didn\'t get one.','status' => 200
                ]);

            }
        }
    }


    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email',
            'password' => 'required|confirmed',
            'code' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['response'=>$validator->errors(), 'message'=>'The password and confirm password do not match !!!.','status'=>422]);
        } else {
            $user = User::where('email',$request->email)->where('level_id',5)->first();
            if($user){
                $passwordReset = DB::table('password_resets')->where('email',$request->email)->first();
                $code = substr($passwordReset->token,3,5);
                if($code == $request->code){
                    $user->password = Hash::make($request->password);
                    $user->save();
                    DB::table('password_resets')->where('email',$request->email)->delete();
                    return response()->json(['message' => 'Password reset successfully. Please proceed to login.', 'status' => 200]);
                } else {
                    return response()->json(['message'=>'The verification code doesnot match','status'=>500]);
                }
            } else {
                return response()->json(['message'=>'User not found','status'=>401]);
            }

        }
    }

    protected function credentials(Request $request)
    {
        return $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );
    }

    protected function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);
        $user->setRememberToken(Str::random(60));
        $user->save();
    }

    public function profileEdit(Request $request, $identification_types_id=null)
    {
        if($request){
            $access_token = $request->header('X-Access-Token');
            if($access_token){
                $user = User::where('api_token', $access_token)->where('level_id',5)->first();
                if($user){
                    $validator = Validator::make($request->all(), [
                        'email' => ['required',
                            'string',
                            'email',
                            'max:191',
                            Rule::unique('users')
                                ->where(function ($query) use ($request, $user){
                                    return $query->where('application_id',$user->application_id);
                                })->ignore($user->id)
                        ],
                        'first_name' => 'required|min:2|max:55',
                        'last_name' => 'required|min:2|max:55',
                        'phone_number' => 'required',
                        'dob' => 'required',
                        'issued_by' => 'nullable',
                        'id_number' => 'nullable',
                        'id_type' => 'nullable',
                        'expiry_date' => 'nullable',
                        'street' => 'required',
                        'suburb' => 'required',
                        'postcode' => 'required',
                        'state' => 'required',
                        'country_list_id' => 'required',
                    ]);
                    if($validator->fails()){
                        return response()->json(['response' => $validator->errors(), 'message'=>'Invalid entry.','status'=>422]);
                    }

                    DB::beginTransaction();
                    try{

                        $person = Person::find($user->person_id);
                        $person->first_name = $request['first_name'];
                        $person->last_name = $request['last_name'];
                        $person->dob = insert_dateformat($request['dob']);
                        $person->email = strtolower($request['email']);
                        $person->save();
                        $user->email = strtolower($request['email']);
                        $user->save();

                        $person_address = PersonAddress::firstOrCreate([
                            'person_id' => $person->id,
                            'current' => 1,
                            'address_status_id' => 1
                        ]);

                        if ($person_address->address_id != 0) {
                            $address = Address::find($person_address->address_id);
                            $address->street = $request['street'];
                            $address->suburb = $request['suburb'];
                            $address->postcode = $request['postcode'];
                            $address->state = $request['state'];
                            $address->country_list_id = $request['country_list_id'];
                            $address->save();
                        }
                        else {
                            $address = Address::create([
                                'street' => $request['street'],
                                'suburb' => $request['suburb'],
                                'postcode' => $request['postcode'],
                                'state' => $request['state'],
                                'country_list_id' => $request['country_list_id'],
                            ]);

                        }
                        $person_address->address_id = $address->id;
                        $person_address->save();

                        $person_phone = PersonPhone::where('person_id', $person->id)->where('current', 1)->first();

                        if (!empty($person_phone)) {
                            $phone = Phone::find($person_phone->phones_id);
                            $phone->number = $request['phone_number'];
                            $phone->save();
                        } else {
                            $phone = new Phone();
                            $phone_id = $phone->add($request['phone_number']);

                            PersonPhone::create([
                                'person_id' => $person->id,
                                'current' => 1,
                                'phones_id' => $phone_id
                            ]);
                        }


                        $sender = Sender::where('person_id',$person->id)
                          //  ->where('added_by',$user->id)
                            ->first();

                        $identification = Identification::where('senders_id', $sender->id)->orderBy('identification_id','desc')->first();

                        $identification->issued_by = $request['issued_by'];
                        $identification->id_number = $request['id_number'];
                        $identification->identification_types_id = $request['id_type'];
                        $identification->expiry_date = insert_dateformat($request['expiry_date']);

                        if($identification->isDirty()){
                            $message = 'Your identification is currently under review by admin.';
                            $user->notify(new IdentificationRequestNotification($message));

                            $identification_new = Identification::create([
                                'issued_by' => $request['issued_by'],
                                'id_number' => $request['id_number'],
                                'identification_status_id' => 1,
                                'identification_types_id' => $request['id_type'],
                                'state' => $request['state'],
                                'expiry_date' => insert_dateformat($request['expiry_date']),
                                'senders_id' => $sender->id,
                                'identification_documents_id' => $identification->identification_documents_id,
                                'current' => 1
                            ]);
                            if($identification_new){
                                $sender->sender_status_id = 5;
                                $sender->save();
                            }
                        }

                        if (\Illuminate\Support\Facades\Request::hasfile('image')) {

                            if(!$identification->isDirty()){
                                $message = 'Your identification is currently under review by admin.';
                                $user->notify(new IdentificationRequestNotification($message));
                            }
                            $file = $request['image'];
                            $destinationPath = 'identification';
                            $fileName = date('Y-m-d-H-i-s') . $file->getClientOriginalName();
                            $file->move($destinationPath, $fileName);

                            $document = Document::create([
                                'type' => '',
                                'user_id' => $user->id,
                                'name' => $fileName,
                                'shelf_location' => '',
                            ]);

                            $fileName1 = null;
                            if(\Illuminate\Support\Facades\Request::hasfile('image1')){
                                $file1 = $request['image1'];
                                $fileName1 = date('Y-m-d-H-i-s') . $file1->getClientOriginalName();
                                $file1->move($destinationPath, $fileName1);

                                $document->name1 = $fileName1;
                                $document->save();
                            }
                            $identification_document = IdentificationDocument::create([
                                'document_id' => $document->id,
                            ]);

                            $final_identification = Identification::where('senders_id', $sender->id)->orderBy('identification_id','desc')->first();
                            $final_identification->identification_documents_id = $identification_document->id;
                            $final_identification->save();
                        }


                        DB::commit();
                    } catch (\Exception $e){
                        DB::rollback();
                        $message = 'Sorry profile could not be edited.';

                        if (env('APP_DEBUG'))
                            $message = $e->getMessage() . 'Line #' . $e->getLine();
                        return response()->json(['message' => $message, 'status' => 404]);
                    }
                    $user_model = new User();
                    $user_details = $user_model->getDetails($user->id);
                    $user_details->image =  url('/') . '/identification/' . $user_details->image;
                    $user_details->identification_types_id = $identification_types_id;
                    $user_details->image1 =  url('/') . '/identification/' . $user_details->image1;
                    return response()->json(['response'=>$user_details,'message' => 'Success', 'status' => 200]);
                } else {
                    return response()->json(['message' => 'User not found.', 'status' => 401]);
                }
            } else {
                return response()->json(['message' => 'Access token isnot set','status' => 404]);
            }
        }
    }

    public function logout(Request $request){
        if($request){
            Auth::logout();
            if(Auth::check()){
                return response()->json(['message' => 'Failed to logout.', 'status' => 500]);
            } else {
                return response()->json(['message' => 'Logged out successfully.', 'status' => 200]);
            }
        }
        return response()->json(['message' => 'Failed', 'status' => 404]);
    }

    public function checkExistingUser(Request $request){
        /*$validator = Validator::make($request->all(), [
            'email' => 'required|unique:person,email,application_id',
        ]);*/
        $application = getAppDetails();
        $user = User::where('email',$request->email)->where('application_id', $application->id)->first();

        $admin_phone = '('.$application->phone_number.')';
        $message = 'You have already registered. Please go to forget password or contact admin '.$admin_phone.'.';
        if(!empty($user)) {
        //if ($validator->fails()) {
            //if($validator->errors()->first('email') == 'The email has already been taken.' && !empty($user)){
              //  return response()->json(['response'=>$validator->errors(), 'message'=>$message,'status'=>404]);
            //} elseif($validator->errors()->first('email') == 'The email has already been taken.' && empty($user)){
                return response()->json([//'response'=>$validator->errors(),
                    'message'=>$message,'status'=>404]);
            //}
            return response()->json(['response'=>$validator->errors(), 'message'=>$message,'status'=>404]);
        } else {
            return response()->json(['message'=>'The email is available for the registration. Please proceed.','status'=>200]);
        }
    }

    public function changePassword(Request $request){
        if($request){
            $access_token = $request->header('X-Access-Token');
            if($access_token){
                $user = User::where('api_token', $access_token)->where('level_id',5)->first();
                if($user){
                    DB::beginTransaction();
                    try{
                        $validator = Validator::make($request->all(), [
                            //'current_password' => 'required',
                            'password_confirmation' => 'required',
                            'password' => 'required|string|min:6|confirmed',

                        ]);
                        if($validator->fails()){
                            return response()->json(['response'=>$validator->errors(), 'message'=>'Failed','status'=>422]);
                        } else {
                            $data = $request->all();
                            if (isset($data['current_password']) && (!Hash::check($data['current_password'], $user->password))) {
                                return response()->json(['message' => 'Current password is invalid', 'status' => 400]);
                            } else {
                                unset($data['password_confirmation']);
                                unset($data['current_password']);
                                $user->password = Hash::make($data['password']);
                                $user->save();
                                DB::commit();
                                return response()->json(['message' => 'Password change successfully!', 'status' => 200]);
                            }
                        }

                    } catch (\Exception $e){
                        DB::rollback();
                        $message = 'Sorry password could not be changed.';
                        if (env('APP_DEBUG'))
                            $message = $e->getMessage() . 'Line #' . $e->getLine();
                        return response()->json(['message' => $message, 'status' => 404]);
                    }
                    return response()->json(['message' => 'Password changed successfully!', 'status' => 200]);
                } else {
                    return response()->json(['message' => 'User not found.', 'status' => 401]);
                }
            } else {
                return response()->json(['message' => 'Access token isnot set','status' => 404]);
            }
        }
        return response()->json(['message' => 'Failed', 'status' => 404]);
    }
    public function checkEmailAvailability(Request $request): JsonResponse
    {
        $rules = [
            'email' => ['required',Rule::unique('users','email')]
        ];
        $request->validate($rules);
        $type = 'register';
        $otp = $this->generateOtpService->generate($request->get('email'), $type);
        $data = ['otp' => $otp,'email' => $request->get('email')];
        $data['subject'] = 'Register Verification OTP';
        $this->otpMailService->send($data['email'], $data['otp']->otp, $data['subject']);
        return response()->json(['message' => 'Otp sent to the email', 'status' => 200]);
    }
    public function verifyEmailNew(Request $request): JsonResponse
    {
        $rules = [
            'email' => 'required|string|email|max:191',
            'otp' => 'required|numeric|digits:6',
        ];
        $data = $request->validate($rules);
        $verified = $this->verifyOtpService->verify($data['otp'],'register',$data['email']);
        if($verified){
            return response()->json(['message' => 'Success', 'status' => 200]);
        }
        return response()->json(['message' => 'Invalid otp', 'status' => 404],404);
    }

     public function forgotPasswordOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:191'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'response' => $validator->errors(),
                'message' => 'Invalid entry.',
                'status' => 422
            ]);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found.',
                'status' => 404
            ]);
        }

        $otp = $this->generateOtpService->generate($request->email,'forgot-password')->otp;


        if (!$otp) {
            return response()->json([
                'message' => 'Password reset OTP unsuccessful.',
                'status' => 400
            ]);
        }

        $person = Person::find($user->person_id);

        if (!$person) {
            return response()->json([
                'message' => 'Associated person record not found.',
                'status' => 400
            ]);
        }

        $subject = 'Forgot Password OTP';

        $this->sendOtpMailService->send($user->email, $otp, $subject);

        if (config('services.otp.send_in_response') === 'yes') {
            return response()->json([
                'message' => 'Password reset OTP sent to your email.',
                'otp' => $otp,
                'status' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Password reset OTP sent to your email.',
                'status' => 200
            ]);
        }
    }

    /**
     * @throws \Exception
     */
    public function forgotPasswordChange(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:191',
            'otp' => 'required|min:6|max:6',
            'password' => 'required|min:8|max:64',
            'password_confirmation' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'response' => $validator->errors(),
                'message' => 'Invalid entry.',
                'status' => 422
            ]);
        }

        $otp = Otp::where([
            ['otp', $request->otp],
        ])->first();

        if (!$otp) {
            return response()->json([
                'message' => 'Invalid Otp',
                'status' => 400
            ]);
        }

        $user = User::where('email', $otp->email)->first();

        if (!$user || $user->email !== $request->email) {
            return response()->json([
                'message' => 'Invalid email address.',
                'status' => 400
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();
        $otp->delete();

        return response()->json([
            'message' => 'Password successfully changed.',
            'status' => 200
        ]);
    }

    public function resendOtp(Request $request)
    {
        $rules = [
            'email' => ['required',Rule::unique('users','email')]
        ];
        $request->validate($rules);
        $type = 'register';
        $otp = $this->generateOtpService->generate($request->get('email'), $type);
        $data = ['otp' => $otp,'email' => $request->get('email')];
        $data['subject'] = 'Register Verification OTP';
        $this->otpMailService->send($data['email'], $data['otp']->otp, $data['subject']);
        return response()->json(['message' => 'Otp sent to the email', 'status' => 200]);
    }

    public function resendActivationCode(ResendActivationCodeRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if(!$user){
            return response()->json([
                'message' => 'User not found.',
            ],404);
        }
        $newAuthCode = substr(str_shuffle("0123456789"), 0, 6);
        $user->auth_code = $newAuthCode;
        $user->save();
        $userData = $user->refresh();
        $application = getAppDetails();
        $this->sendActivationEmail($userData, $userData->person_id,$application);
        return response()->json([
            'message' => 'Activation code sent to the email provided.',
        ],200);
    }
}
