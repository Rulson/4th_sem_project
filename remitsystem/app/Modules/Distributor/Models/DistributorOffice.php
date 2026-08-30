<?php

namespace App\Modules\Distributor\Models;

use App\Modules\User\Models\Address;
use App\Modules\User\Models\Person;
use App\Modules\User\Models\PersonAddress;
use App\Modules\User\Models\PersonPhone;
use App\Modules\User\Models\Phone;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DistributorOffice extends Model
{
    //
    protected $table ='distributor_offices';
    protected $fillable = ['companies_id','active','notes'];
    public $timestamps = false;

    public function getDistributorOffice(){
$distributor = DistributorOffice::leftJoin('companies','companies.id','=','distributor_offices.companies_id')
    ->select('companies.id as companyId','companies.company_name as companyName')
    ->get();
return $distributor;

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
            ->leftJoin('distributor_users','distributor_users.user_id','=','users.id')
            ->select(['users.id as user_id','distributor_users.role_id as distributor_level_id', 'person.first_name', 'person.last_name', 'person.dob', 'addresses.*', 'users.email', 'phones.number', 'levels.name as role', 'levels.name as level_id', 'user_status.name as status', 'users.created_at', DB::raw('CONCAT_WS(" ", person.first_Name, NULLIF(person.middle_name,""), person.last_Name) AS full_name')])
            ->find($user_id);
        return $user;
    }
    public function addUser(array $request,$id)
    {
        DB::beginTransaction();

        try {
            // Saving client profile
            $person = Person::create([
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name'],
                'dob' => $request['dob'],
                'email' => strtolower($request['email'])
            ]);

            $user = User::create([
                'level_id' => 4,
                'user_status_id' => 2, // Active
                'person_id' => $person->id,
                'email' => strtolower($request['email']),
                'password' => Hash::make($request['password'])
            ]);
            $user->auth_code = uniqid().md5($user->id);
            $user->save();
            $distributorOfficeId = DistributorOffice::where('companies_id',$id)->first();

            $distributor_user = DB::table('distributor_users')->insert([
                'user_id' => $user->id,
                'distributor_office_id' => $distributorOfficeId->id,
                'role_id' => $request['distributor_level_id']

            ]);

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
    public function editUser(array $request, $user_id)
    {
        DB::beginTransaction();

        try {
            $user = User::find($user_id);
            $user->level_id = 4;
            $user->email = strtolower($request['email']);

            if($request['password'] != '')
                $user->password = Hash::make($request['password']);
            $user->save();

            // Saving client profile
            $person = Person::find($user->person_id);
            $person->first_name = $request['first_name'];
            $person->last_name = $request['last_name'];
            $person->dob = $request['dob'];
            $person->save();

            // Edit address section
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
            $distributor = DB::table('distributor_users')->where('user_id',$user_id)->update([
                'role_id'=>$request['distributor_level_id']
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


}
