<?php

namespace App\Modules\Auth\Controllers;

use App\Modules\Agent\Models\Agent;
use App\Modules\Auth\Service\SendSenderRegisteredEmailService;
use App\Modules\EmailTemplate\Models\EmailTemplate;
use App\Modules\Referral\Models\Referral;
use App\Modules\Sender\Models\Document;
use App\Modules\Sender\Models\Identification;
use App\Modules\Sender\Models\IdentificationDocument;
use App\Modules\Sender\Models\Sender;
use App\Modules\SMS\Models\sms;
use App\Modules\User\Models\Address;
use App\Modules\User\Models\AusStates;
use App\Modules\User\Models\CountryList;
use App\Modules\User\Models\Person;
use App\Modules\User\Models\PersonAddress;
use App\Modules\User\Models\PersonPhone;
use App\Modules\User\Models\Phone;
use App\Modules\User\Models\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Str;
use Laracasts\Flash\Flash;
use function Sodium\add;

class RegisterController extends Controller
{

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        private SendSenderRegisteredEmailService $sendSenderRegisteredEmailService,
    )
    {
        $this->middleware('guest');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     */
    protected function create(array $data)
    {
        $person = Person::create([
            'first_name' => $data['given_name'],
            'last_name' => $data['last_name'],
            'email' => strtolower($data['email']),

        ]);
        $users = User::create([
            'email' => strtolower($data['email']),
            'password' => Hash::make($data['password']),
            'person_id' => $person->id,
            'level_id' => 5,
            'user_status_id' => 1,
            'active' => 0
        ]);
        $users->auth_code = md5($users->id);
        $users->save();
        return $users;
    }


    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Auth::register');
    }

    /**
     * Handle a registration request for the application.
     */
    public function postRegister(Request $request)
    {
        $rules = [
            'email' => 'required|string|email|max:191',
            'password' => 'required|string|min:6|confirmed'
        ];
        $this->validate($request, $rules);

        $request->session()->put('newRegistrationData', $request->all());
        return redirect()->route('register.newClient');
    }

    public function handleInitialRegistration(Request $request)
    {
        $rules = [
            'email' => 'required|string|email|max:191',
            'password' => 'required|string|min:6|confirmed'
        ];
        $this->validate($request, $rules);

        $request->session()->put('newRegistrationData', $request->all());
        $agent_id = $request->get('agent_id') ?? null;
        return redirect()->route('register.newClient', compact('agent_id'));

    }

    public function newClient(Request $request)
    {
        $countries = CountryList::orderBy('name', 'asc')->pluck('name', 'id');
        $agent_id = $request->get('agent_id') ?? null;
        return view('Auth::newClient', compact('countries', 'agent_id'));
    }

    public function storeUserClient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:2|max:55',
            'last_name' => 'required|min:2|max:55',
            'phone_number' => ['required', 'regex:/^(?:\+?61|0)4([0-9]{8})$/'],
            'dob' => 'required',
            'issued_by' => 'required',
            'id_number' => 'required',
            'id_type' => 'required',
            'expiry_date' => 'required',
            'image' => 'required |max:10000',
            'address_proof' => 'required |max:10000',
            'street' => 'required',
            'suburb' => 'required',
            'postcode' => 'required',
            'state' => 'required',
            'country_list_id' => 'required',
        ], [
            'phone_number.regex' => 'Please Enter Valid Phone Number'
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)
                ->withInput();
        }

        $userData = $request->session()->get('newRegistrationData');

        $emailCheck = Sender::join('person', 'person.id', '=', 'senders.person_id')
            ->where('person.email', $userData['email'])
            ->first();


        if (isset($emailCheck)) {

            $addedByCheck = User::where('id', $emailCheck->added_by)->select('users.level_id')->first();

            if (!empty($addedByCheck) && ($addedByCheck->level_id == 1 || $addedByCheck->level_id == 2)) {
                flash($userData['email'] . ' is already taken please contact system admin.')->error();
                return redirect()->back();
            }
        }
        $application = getAppDetailsForWeb();

        if (isset($request->referral_code)) {
            $referral_code_validation = validateReferralCode($request->referral_code, $application->id);

            if ($referral_code_validation != 'true') {
                return redirect()->back()->withErrors($referral_code_validation)
                    ->withInput();
            }
        }


        DB::beginTransaction();
        try {

            $person = Person::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => strtolower($userData['email']),
                'dob' => insert_dateformat($request->dob),
            ]);

            $user_model = new User();
            if ($user_model->validate($userData, $application->application_id)) {
                $users = User::create([
                    'email' => strtolower($userData['email']),
                    'password' => Hash::make($userData['password']),
                    'person_id' => $person->id,
                    'level_id' => 5,
                    'user_status_id' => 1,
                    'active' => 0,
                    'api_token' => getApiToken(),
                    'application_id' => $application->id,
                    'referral_code' => generateReferralCode($application->id)
                ]);
                $users->auth_code = substr(str_shuffle("0123456789"), 0, 6);
                $users->save();
            } else {
                DB::rollback();
                $errors = $user_model->errors();
                flash($errors)->error();
                return redirect()->back();
            }

            $personData = Person::find($person->id);
            $personData->dob = Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d');
            $personData->save();

            $phone = new Phone();
            $phone->number = $request->phone_number;
            $phone->save();

            PersonPhone::create([
                'phones_id' => $phone->id,
                'person_id' => $person->id,
                'current' => 1
            ]);

            $address = Address::create([
                'street' => $request['street'],
                'suburb' => ucwords($request['suburb']),
                'postcode' => $request['postcode'],
                'state' => $request['state'],
                'country_list_id' => $request['country_list_id'],
            ]);
            PersonAddress::create([
                'address_id' => $address->id,
                'person_id' => $person->id,
                'current' => 1,
                'address_status_id' => 1
            ]);

            $agent = Agent::find($request->get('agent_id'));
            if ($agent) {
                $addedby = $agent->user_id;
            } else {
                $addedby = $application->agent_id;
            }
            $sender_model = new Sender();

            if ($sender_model->validate($userData, $addedby)) {
                $sender = Sender::create([
                    'person_id' => $person->id,
                    'added_by' => $addedby,
                    'sender_status_id' => 1
                ]);
                if ($agent) {
                    DB::table('agent_senders')->insert([
                        'agents_id' => $agent->id,
                        'senders_sender_id' => $sender->id
                    ]);
                }
            } else {
                DB::rollback();
                $errors = $sender_model->errors();
                flash($errors)->error();
                return redirect()->back();
            }

            $sender_model->checkSimilarity($request->all(), $sender->id, $users->application_id);

            $identification_document_id = $this->uploadIdentification(
                $request['image'],
                $person->id,
                $users->id,
                $request['image1'] ?? null,
                $request['address_proof'] ?? null
            );

            Identification::create([
                'issued_by' => $request['issued_by'],
                'id_number' => $request['id_number'],
                'identification_status_id' => 2,
                'identification_types_id' => $request['id_type'],
                'expiry_date' => insert_dateformat($request->expiry_date),
                'senders_id' => $sender->id,
                'identification_documents_id' => $identification_document_id,
                'current' => 1
            ]);
            /* $sms = new sms();
            $sms->sendActivationSms($users,$person->id); */
            $suburb = AusStates::where('name', 'like', '%' . $request['suburb'] . '%')->where('type', 'aus_suburb')->first();
            if (!$suburb) {
                $parent_id = AusStates::select('id')->where('name', $request['state'])->first()->id;
                AusStates::create(['name' => ucwords($request['suburb']), 'type' => 'aus_suburb', 'parent_id' => $parent_id, 'postcode' => $request['postcode']]);
            }
            if (isset($request->referral_code)) {
                if (!empty($request->referral_code)) {
                    $referral = new Referral();
                    $referral->add($users->id, $request->referral_code, $application->discount_percent);
                }
            }
            DB::commit();
            $this->sendActivationEmail($users, $person->id, $application);
            try{
                $this->sendSenderRegisteredEmailService->sendToAdmin($sender);
            }catch (\Throwable $exception){
                dd($exception);
                Log::critical('Failed to send sender registered email to admin.');
                Log::critical($exception->getMessage());
                Log::critical($exception->getTraceAsString());
            }

            //Flash::success('Your Account has been created successfully. The verification code has been sent to your inbox. Please enter the verification code sent to your email. Please check junk folder for email as well.');
            Flash::success('Your Account has been created successfully. The verification code has been sent to your inbox. Please wait for approval by admin or contact us.');
        } catch (\Exception $e) {
            Log::error($e->getTraceAsString());
            DB::rollback();
            $message = 'Sorry Client could not be saved. Please contact Admin';
            flash($message)->error();
            if (env('APP_DEBUG'))
                $message = $e->getMessage() . 'Line #' . $e->getLine();
        }
        return redirect()->route('verifyEmail');
    }

    public function verifyEmail()
    {
        return view('Auth::verifyEmail');
    }

    public function verifiedEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'verification_code' => 'required',

        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)
                ->withInput();
        }
        $user = User::where('email', '=', $request->email)->where('level_id', 5)->first();
        if ($user) {
            if ($user->auth_code == $request->verification_code) {
                $user->user_status_id = 1;
                $user->auth_code = '';
                $user->email_verified_at = date('Y-m-d H:i:s');
                $user->save();
                Flash::success('Your email has been verified. You will receive an email once we approve the documents. Thank you.');
                return redirect()->route('login');
            } else {
                Flash::error('Verification Code does not match.');
            }
        } else {
            Flash::error('Email not found. Please enter correct email.');
        }
        return redirect()->back();
    }

    public function sendActivationEmail($userData, $person_id, $application)
    {


        $userName = Person::where('id', $person_id)->first();

        $url = route('activate', $userData['auth_code']);
        $email_template = getEmailTemplate('type', 'verification', $application);

        if ($email_template) {
            $subject = $email_template->subject;
            $body = $email_template->message;
            $data_array_parse = array(
                'FULL_NAME' => $userName['first_name'] . ' ' . $userName['last_name'],
                'VERIFICATION_CODE' => $userData['auth_code'],
            );
            $data_array_parse = format_template_array($application, $data_array_parse);
            /*if(!empty($application->playstore_url)){
                $body = buildTemplate($body,$application);
            }*/
            $subject = parseTemplate($subject, $data_array_parse);
            $body = parseTemplate($body, $data_array_parse);
            $view = 'EmailTemplate::Email/email';
        } else {
            $subject = 'Verify your email';
            $body = 'Thank you for Registration. Your verification code is ' . $userData['auth_code'] . '';
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

    public function activate($activate_token)
    {
        $user = User::where('auth_code', $activate_token)->first();
        if ($user) {
            $user->user_status_id = 2;
            $user->auth_code = '';
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->save();
            flash('Account activated successfully.')->success();
        } else {
            flash('Account could not be activated.')->error();

        }
        return redirect('login');
    }

    public function activateSender()
    {
        $rules = array('email' => 'required', 'new_password' => 'required|min:6', 'new_password_confirmation' => 'required|same:new_password|min:6');
        $user_reset = User::where('auth_code', Request::only('confirmed_code'))->first();
        if (!empty($user_reset)) {
            $validator = Validator::make(Request::all(), $rules);
            if ($validator->fails())
                return Redirect::back()->withErrors($validator)->withInput();
            $newpassword = Hash::make(Request::get('new_password'));
            $user_reset->update([
                'password' => $newpassword,
                'user_status_id' => 2,
                'auth_code' => '',
                'active' => 1
            ]);
            flash('Account activated successfully.')->success();
        } else {
            flash('Invalid Link.')->error();
        }
        return redirect('/login');
    }


    public function uploadIdentification($image, $id = 0, $user_id, $image1 = null, $address_proof = null)
    {
        $file = $image;
        if (\Illuminate\Support\Facades\Request::hasfile('image')) {
            $destinationPath = 'identification';
            $fileName = date('Y-m-d-H-i-s') . $file->getClientOriginalName();
            $file->move($destinationPath, $fileName);

            $document = Document::create([
                'type' => '',
                'user_id' => $user_id,
                'name' => $fileName,
                'shelf_location' => '',
            ]);
            if (isset($image1) && \Illuminate\Support\Facades\Request::hasfile('image1')) {
                $file1 = $image1;
                $fileName1 = date('Y-m-d-H-i-s') . $image1->getClientOriginalName();
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

            $identification_document = IdentificationDocument::create([
                'document_id' => $document->id,
            ]);
            return ($identification_document->id);
        }

    }

    public function setSenderPassword($token)
    {
        if (is_null($token)) {
            App::abort(404);
        }
        $user = User::where('auth_code', $token)->first();
        if ($user) {
            return view('Auth::senderPasswordReset', compact('user'))->with('token', $token);
        } else {
            App::abort(404);
        }
    }

}
