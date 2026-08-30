<?php

namespace App\Modules\User\Controllers;

use App\Modules\Agent\Models\Agent;
use App\Modules\Application\Constants\DomainNames;
use App\Modules\Application\Models\Application;
use App\Modules\User\Models\Address;
use App\Modules\User\Models\Person;
use App\Modules\User\Models\PersonAddress;
use App\Modules\User\Models\PersonPhone;
use App\Modules\User\Models\Phone;
use App\Modules\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Clients\Entities\Clients;
use Modules\User\Entities\UserClient;

class DeleteUserController
{
    public function showDeletePage(Request $request)
    {
        return view('User::Delete.delete-account');
    }

    public function deleteAccount(Request $request)
    {
        $rules = [
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
            'application_id' => 'nullable'
        ];
        $request->validate($rules);
        $credentials = $request->only('email', 'password');
        if (!Auth::validate($credentials)) {
            return redirect()->back()->withErrors(['password' => 'Invalid password'])->withInput();
        }
        if($request->get('application_id')){
            $application = Application::find($request->get('application_id'));
        }else{
            $application = $this->getApplication();
        }
        if(!$application){
            return redirect()->route('user.dashboard');
        }
        $user = User::where('email', $request->email)->where('application_id', $application->id)->first();
        if(!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }
        if ($user->application_id != $application->id) {
            return redirect()->back()->with('error', 'User not found.');
        }
        Auth::logout();
        $person = Person::find($user->person_id);
        $isAgentAccount = Agent::where('person_id', $person->id)->first();
        if ($isAgentAccount) {
            return back()->with('error', 'You cannot delete agent account.')->withInput();
        }
        DB::beginTransaction();
        try {
            // soft deleting.
            $user->delete();
            DB::commit();
            return redirect()->route('login')->with('account_deleted', 'Account deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function getApplication()
    {
        $domainName = request()->getHttpHost();
        $application = Application::where('domain_url', $domainName)->first();
        if(empty($application)){
            return Application::first();
        }
        return $application;
    }
}
