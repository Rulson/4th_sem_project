<?php

namespace App\Modules\User\Models;

use App\Modules\Agent\Models\Agent;
use App\Modules\Agent\Models\AgentExchangeRate;
use App\Modules\Sender\Models\Identification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Notifications\MyResetPassword;
use Illuminate\Support\Str;
class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    const ACTIVE = 1;
    const INACTIVE = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password', 'person_id', 'timezone', 'level_id', 'signature', 'user_status_id', 'remember_token', 'active','api_token','firebase_token','application_id','referral_code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    private $error;


    public function validate( array $data,$application_id)
    {

            $user = User::where('email',$data['email'])->where('application_id',$application_id)->first();

            if(!$user){
                return true;
            }
            else{
                $this->error = $data['email'] . ' is already taken please contact system admin.';

                return false;
            }

    }

    public function errors()
    {
        return $this->error;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MyResetPassword($token));
    }

    public function getAll()
    {
        $users = User::join('person', 'person.id', '=', 'users.person_id')
            ->join('user_status', 'users.user_status_id', '=', 'user_status.id')
            ->join('levels', 'users.level_id', '=', 'levels.id')
            ->leftJoin('person_phones', function ($q) {
                $q->on('person_phones.person_id', '=', 'person.id');
                $q->where('person_phones.current', '=', 1);
            })
            ->leftJoin('phones', 'person_phones.phones_id', '=', 'phones.id')
            ->select(['users.id as user_id', 'person.first_name', 'person.last_name', 'users.email', 'users.user_status_id', 'phones.number', 'levels.name as role', 'user_status.name as status', 'users.created_at', DB::raw('CONCAT_WS(" ", person.first_Name, NULLIF(person.middle_name,""), person.last_Name) AS full_name')]);
        if (Auth::user()->level_id == 2) {
            $users = $users->whereIn('level_id', [2,5,7,8]);
        }
        $users = $users->orderBy('users.id', 'desc')->get();
        return $users;
    }

    public function add(array $request)
    {
        DB::beginTransaction();

        try {
            // Saving client profile
            $person = Person::create([
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name'],
                'dob' => Carbon::createFromFormat('d/m/Y', $request['dob'])->format('Y-m-d'),
            ]);

            $user = User::create([
                'level_id' => $request['level_id'],
                'user_status_id' => 2, // Active
                'person_id' => $person->id,
                'email' => strtolower($request['email']),
                'password' => Hash::make($request['password']),
                'api_token'=> Str::random(32)
            ]);
            $user->auth_code = md5($user->id);
            $user->save();


            // Add address
            $address = Address::create([
                'street' => $request['street'],
                'suburb' => $request['suburb'],
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

            // Add Phone Number
            $phone = new Phone();
            $phone_id = $phone->add($request['number']);
            PersonPhone::create([
                'phones_id' => $phone_id,
                'person_id' => $person->id,
                'current' => 1
            ]);

            DB::commit();
            return $user->id;
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return false;
        }
    }

    public function getDetails($user_id)
    {
        $user = User::join('person', 'person.id', '=', 'users.person_id')
            ->join('user_status', 'users.user_status_id', '=', 'user_status.id')
            ->join('levels', 'users.level_id', '=', 'levels.id')
            ->leftJoin('person_phones', function ($q) {
                $q->on('person_phones.person_id', '=', 'person.id');
                $q->where('person_phones.current', '=', 1);
            })
            ->leftJoin('phones', 'person_phones.phones_id', '=', 'phones.id')
            ->leftJoin('person_address', function ($q) {
                $q->on('person_address.person_id', '=', 'person.id');
                $q->where('person_address.current', '=', 1);
            })
            ->leftJoin('addresses', 'person_address.address_id', '=', 'addresses.id')
            ->join('senders','person.id','=','senders.person_id')
            ->join('identifications','identifications.senders_id','=','senders.id')
            ->select(['users.id as user_id','users.api_token', 'person.first_name', 'person.last_name',
                'person.dob', 'addresses.*', 'users.email', 'phones.number', 'levels.name as role', 'levels.name
                as level_id', 'users.level_id as levelid', 'user_status.name as status', 'senders.created_at',
                'identifications.expiry_date','identifications.issued_by','identifications.id_number','identifications.identification_types_id','senders.id as sender_id','users.referral_code',
                DB::raw('CONCAT_WS(" ", person.first_Name, NULLIF(person.middle_name,""), person.last_Name)
                AS full_name')])
            ->find($user_id);
            if($user){
                $identification = Identification::join('identification_documents', 'identification_documents.id', '=', 'identifications.identification_documents_id')
                    ->join('documents', 'documents.id', '=', 'identification_documents.document_id')
                    ->where('identifications.current', 1)
                    ->where('identifications.Identification_status_id', 2)
                    ->where('identifications.senders_id',$user->sender_id)
                    ->select('identifications.identification_types_id','identifications.senders_id as senderId','identifications.id_number', 'identifications.issued_by', 'identifications.id_number', 'identifications.expiry_date', 'documents.name','documents.name1')
                    ->orderBy('documents.id', 'desc')->first();
                if($identification){
                    $user->image = $identification->name;
                    $user->image1 = $identification->name1;
                }

                $user->pin_set = false;
                $pinExists = User::find($user_id)->pin;
                if (!empty($pinExists)) {
                    $user->pin_set = true;
                }

            }
        return $user;
    }

    public function getUserDetailsByID($user_id){
        $user = User::join('person', 'person.id', '=', 'users.person_id')
            ->join('user_status', 'users.user_status_id', '=', 'user_status.id')
            ->join('levels', 'users.level_id', '=', 'levels.id')
            ->leftJoin('person_phones', function ($q) {
                $q->on('person_phones.person_id', '=', 'person.id');
                $q->where('person_phones.current', '=', 1);
            })
            ->leftJoin('phones', 'person_phones.phones_id', '=', 'phones.id')
            ->leftJoin('person_address', function ($q) {
                $q->on('person_address.person_id', '=', 'person.id');
                $q->where('person_address.current', '=', 1);
            })
            ->leftJoin('addresses', 'person_address.address_id', '=', 'addresses.id')
            ->select(['users.id as user_id','users.api_token', 'person.first_name', 'person.last_name',
                'person.dob', 'addresses.*', 'users.email', 'phones.number', 'levels.name as role', 'levels.name
                as level_id', 'users.level_id as levelid', 'user_status.name as status', 'users.created_at','users.application_id',
                DB::raw('CONCAT_WS(" ", person.first_Name, NULLIF(person.middle_name,""), person.last_Name)
                AS full_name')])
            ->find($user_id);
        return $user;
    }

    public function edit(array $request, $user_id)
    {
        DB::beginTransaction();

        try {
            $user = User::find($user_id);
            if (Auth::user()->level_id == 1 || Auth::user()->level_id == 2 || Auth::user()->level_id == 4) {
                $user->level_id = $request['level_id'];
                $user->email = strtolower($request['email']);
                if($request['level_id'] == 5){
                    $user->application_id = $request['application_id'];
                }
                if ($request['password'] != '')
                    $user->password = Hash::make($request['password']);
                $user->save();
            }


            // Saving client profile
            $person = Person::find($user->person_id);
            if (Auth::user()->level_id == 1 || Auth::user()->level_id == 2 || Auth::user()->level_id == 4) {
                $person->first_name = $request['first_name'];
                $person->last_name = $request['last_name'];
                $person->dob = Carbon::createFromFormat('d/m/Y', $request['dob'])->format('Y-m-d');
                $person->save();
            }


            // Edit address section
            if (Auth::user()->level_id == 1 || Auth::user()->level_id == 2 || Auth::user()->level_id == 3 || Auth::user()->level_id == 4) {
                $person_address = PersonAddress::firstOrCreate([
                    'person_id' => $person->id,
                    'current' => 1,
                    'address_status_id' => 1
                ]);
                // Edit old record when exists
                if ($person_address->address_id != 0) {
                    $address = Address::find($person_address->address_id);
                    $address->street = $request['street'];
                    $address->suburb = $request['suburb'];
                    $address->postcode = $request['postcode'];
                    $address->state = $request['state'];
                    $address->country_list_id = $request['country_list_id'];
                    $address->save();

                } else { //remove this one
                    // Add address
                    $address = Address::create([
                        'street' => $request['street'],
                        'suburb' => $request['suburb'],
                        'postcode' => $request['postcode'],
                        'state' => $request['state'],
                        'country_list_id' => $request['country_list_id'],
                    ]);
                    $person_address->address_id = $address->id;
                    $person_address->save();
                }
            }
            if (Auth::user()->level_id == 1 || Auth::user()->level_id == 2 || Auth::user()->level_id == 4) {

                // Edit Phone Number Section
                $person_phone = PersonPhone::where('person_id', $person->id)->where('current', 1)->first();

                //Edit old record when exists
                if (!empty($person_phone)) {
                    $phone = Phone::find($person_phone->phones_id);
                    $phone->number = $request['number'];
                    $phone->save();

                } else {
                    $phone = new Phone();
                    $phone_id = $phone->add($request['number']);

                    PersonPhone::create([
                        'person_id' => $person->id,
                        'current' => 1,
                        'phones_id' => $phone_id
                    ]);
                }
            }

            if(Auth::user()->level_id != 3 && $request['level_id'] == 3){
                $agent_detail = Agent::where('user_id', '=', $user_id)->first();
                if(!$agent_detail){
                    $agentExchange['less_than_service_charge'] = 3;
                    $agentExchange['more_than_service_charge'] = 0;
                    $agentExchange['sending_amount_threshold'] = 0;
                    $agent_exchange = AgentExchangeRate::create($agentExchange);
                    Agent::create([
                        'person_id' => $user->person_id,
                        'agent_exchange_rate_id' => $agent_exchange->id,
                        'user_id' => $user_id,
                        'agent_service_charge' => null,
                    ]);
                }
            }

            DB::commit();
            return $user->id;
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return false;
        }
    }
//    public function deleteUserAccount($user){
//        $person = Person::find($user->person_id);
//        $personAddress = PersonAddress::where('person_id', $user->person_id)->first();
//        if ($personAddress) {
//            $address = Address::find($personAddress->address_id);
//            $address->delete();
//            $personAddress->delete();
//        }
//        $personPhone = PersonPhone::where('person_id', $user->person_id)->first();
//        $phone = Phone::find($personPhone->phones_id);
//        $phone->delete();
//        $personPhone->delete();
//        $person->delete();
//        $user->delete();
//    }
}

