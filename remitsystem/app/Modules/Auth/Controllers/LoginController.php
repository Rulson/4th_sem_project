<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Models\Person;
use App\Modules\User\Models\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Guard $auth)
    {

        $this->middleware('guest')->except('logout');
    }

    function index()
    {
        $application = getApplicationDetail('domain_url',request()->getHttpHost());
        return view('Auth::login',compact('application'));
    }

    function postLogin(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email',
            'password'  => 'required|string:min:6'
        ]);
        $credentials = $this->credentials($request);
        if($credentials == false){
            return redirect('/login')->with('message', 'You have entered an invalid email.');
        }
        if(auth()->attempt($credentials))
        {
            if (auth()->user()->user_status_id != 2) { //If not activated
                Auth::logout();
                return redirect()->route('login')->withInput()->with('message', 'Your account is not activated yet. Once the system admin has approved the account, you will receive an email. Thank you');
            }
            return redirect()->route('user.dashboard');
        }
        else
        {
            return back()->with('message', 'You have entered an invalid email or password!');
        }

    }
    public function credentials(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');

        $user_detail = User::where('email',$request->get('email'))->first();
        if($user_detail){
            if($user_detail->level_id == 5){
                $credentials = array_merge($credentials, ['application_id'=> getAppDetailsForWeb()->id]);
            }
        }
        else {
            return false;
        }
        return $credentials;
    }


    public function logout() {
        Auth::logout();
        return redirect('/login');
    }

    public function sendActionEmail()
    {
        return view('Auth::sendActivationEmail');
    }

    public function resendEmail(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'email'   => 'required|email'
        ]);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator);
        }
        $user = User::where('email',$request->email)->first();
        if(empty($user)){
            Flash::success('Email not found.');
            return redirect()->route('send-activation-email');
        }
        $person_id = $user->person_id;
        $this->sendActivationEmail($user,$person_id);
        Flash::success('An activation email has been sent to your inbox. Please activate your account by clicking activation link sent to your email. Please check junk folder for email as well.');
        return redirect()->route('login');

    }

    public function sendActivationEmail($userData, $person_id)
    {
        $userName = Person::where('id', $userData->person_id)->first();
        $url = route('set.sender.password', $userData['auth_code']);
        $body = 'Thank you for Registration. Please activate the account by clicking on link below.<br> <a href="' . $url . '">Activate Now</a><br> We will review your Uploaded ID and details and confirm your account to access the system.';

        $application = getApplicationDetail('domain_url',request()->getHttpHost());

        $param = [
            'to' => strtolower($userData->email),
            'toName' => $userName['first_name'] . ' ' . $userName['last_name'],
            'body' => $body,
            'subject' => 'Activate your account',
            'fromEmail' => $application->email,
            'fromName' => $application->name
        ];

        Mail::send('Auth::Email/activation', $param, function ($message) use ($param) {
            $message->to($param['to'], $param['toName'])
                ->from($param['fromEmail'], $param['fromName'])
                ->subject($param['subject']);
        });
    }
}
