<?php

namespace App\Modules\SMS\Models;

use App\Modules\Settings\Models\Settings;
use App\Modules\SMS\Models\Customer;
use App\Modules\User\Models\Person;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class sms extends Model
{
    //
    protected $table='sms_log';
    protected $fillable = ['id','transaction_id','receiver_id','send_from','message','created_at','updated_at'];

    public function sms_purchase(array $request)
    {
        try {

            $setting = Settings::first();
            $sms_fee = $setting['sms_fee'];

            $total_purchase_amount = ($sms_fee * $request['sms_credit'])/100;
            $stripe_parameters = $request;
            $stripe_parameters['total_amount'] = $total_purchase_amount;
            $user_id = Auth::user()->id;

            $charge_id = $this->_stripe_payment($stripe_parameters);

            SmsPayment::create([
                'user_id' =>$user_id,
                'amount' => $total_purchase_amount,
                'payment_date' => get_today_date(),
                'payment_type' => 'Credit Card',
                'sms_credit'=>$request['sms_credit'],
                'stripe_transaction_id' => $charge_id,
            ]);
            $setting = Settings::first();
            $current_credit_number = $setting->sms_credit;
            $setting->sms_credit = $current_credit_number + $request['sms_credit'];
            $setting->save();

            return true;
        } catch (\Exception $e) {
            //DB::rollback();
            dd($e);
        }
    }

    public function _stripe_payment(array $req)
    {

        $amount = $req['total_amount'];
        $token = $req['stripeToken'];
        $email = strtolower($req['stripeEmail']);

        $current_user = User::where('id',Auth::user()->id)->first();
        $current_user_detail = Person::where('id',$current_user->person_id)->first();
        $current_user_first_name = $current_user_detail->first_name;
        $current_user_last_name = $current_user_detail->last_name;

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY','sk_test_vbX3dEdyeAlPAp0XPyRVaGAY'));
        $amountCents = $amount*100;


       $emailCheck = Customer::where('email', $email)->value('email');


        // If the email doesn't exist in the database create new customer and customer record
        if (!isset($emailCheck)) {
            // Create a new Stripe customer
            try {
                $customer = \Stripe\Customer::create([
                    'source' => $token,
                    'email' => strtolower($email),
                    'metadata' => [
                        "First Name" => $current_user_first_name,
                        "Last Name" => $current_user_last_name
                    ]
                ]);
            } catch (\Stripe\Error\Card $e) {
                return redirect()->back()
                    ->withErrors($e->getMessage())
                    ->withInput();
            }

            $customerID = $customer->id;

            // Create a customer in the database with Stripe ID
            Customer::create([
                'first_name' => $current_user_first_name,
                'last_name' => $current_user_last_name,
                'email' => strtolower($email),
                'stripe_customer_id' => $customerID,
            ]);
        } else {
            $customerID = Customer::where('email', $email)->value('stripe_customer_id');
        }
        // Charging the Customer with the selected amount
        try {
            // Charging the Customer with the selected amount
            $charge = \Stripe\Charge::create([
                'amount' => $amountCents,
                'currency' => 'aud',
                'customer' => $customerID,
                'metadata' => [
                    'product_name' => 'Condat Solutions Subscription Renew'
                ]
            ]);
            return $charge->id;
        } catch (\Stripe\Error\Card $e) {
            return redirect()->back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

   public function sendActivationSms($user,$person_id)
    {

        $person = Person::leftjoin('person_phones','person_phones.person_id','=','person.id')
            ->leftjoin('phones','phones.id','=','person_phones.phones_id')
            ->where('person.id', $person_id)
            ->select([DB::raw('CONCAT_WS(" ", person.first_Name, NULLIF(person.middle_name,""), person.last_Name) AS name'),'phones.number'])->first();
        $message = 'Thank you for Registration. Your verification code is ' . $user['auth_code'] ;
        $username = '';
        $password = '';
        $settings = Settings::first();
        $source = $settings->phone_number;
        $content = 'username=' . rawurlencode($username) .
            '&password=' . rawurlencode($password) .
            '&to=' . rawurlencode($person->number) .
            '&from=' . rawurlencode($source) .
            '&message=' . rawurlencode($message);

        $ch = curl_init('https://api.smsbroadcast.com.au/api.php');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}
