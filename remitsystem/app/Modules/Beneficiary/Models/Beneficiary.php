<?php

namespace App\Modules\Beneficiary\Models;

use App\Modules\Distributor\Models\DistributorUser;
use App\Modules\Sender\Models\Company;
use App\Modules\Sender\Models\SenderBeneficiary;
use App\Modules\User\Models\Address;
use App\Modules\User\Models\Person;
use App\Modules\User\Models\PersonAddress;
use App\Modules\User\Models\PersonPhone;
use App\Modules\User\Models\Phone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Beneficiary extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'beneficiaries';

    /**
     * The primary key of the table.
     *
     * @var string
     */
    protected $primaryKey = 'beneficiary_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'person_id', 'added_by', 'created_date'
    ];

    /**
     * Disable Laravel's Eloquent timestamps
     */
    public $timestamps = false;

    /*DONE*/
    public function getAll()
    {
        $beneficiaries = Beneficiary::join('person', 'person.id', '=', 'beneficiaries.person_id')
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
            ->select(['beneficiaries.beneficiary_id', 'beneficiaries.created_date as dateAdded', 'beneficiaries.added_by', 'person.first_name', 'person.last_name', 'person.email', 'phones.number', 'addresses.street', DB::raw('CONCAT_WS(" ", person.first_Name, NULLIF(person.middle_name,""), person.last_Name) AS full_name')])
            ->orderBy('beneficiaries.beneficiary_id', 'desc');


        return $beneficiaries->get();
    }

    public function getBeneficiaryAccordingToSender($sender_id)
    {
        $beneficiaries = Beneficiary::join('sender_beneficiaries', 'sender_beneficiaries.beneficiary_id', '=', 'beneficiaries.beneficiary_id')
            ->join('person', 'person.id', '=', 'beneficiaries.person_id')
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
            ->leftJoin('beneficiary_bank_details', 'beneficiary_bank_details.beneficiaries_beneficiary_id', '=', 'beneficiaries.beneficiary_id')
            ->leftJoin('bank_details', 'bank_details.id', '=', 'beneficiary_bank_details.bank_details_id')
            ->where('beneficiary_bank_details.current', 1)
            ->where('sender_beneficiaries.sender_id', $sender_id)
            ->orderby('beneficiaries.beneficiary_id', 'desc')
            ->select(['beneficiaries.beneficiary_id', 'beneficiaries.added_by', 'person.first_name', 'person.last_name', 'phones.number', 'addresses.street', DB::raw('CONCAT_WS(" ", person.first_Name, NULLIF(person.middle_name,""), person.last_Name) AS full_name'), 'bank_details.account_no as accountNo', 'bank_details.bank_name as bankName'])
            ->get();
        return $beneficiaries;
    }

    public function getBeneficiaryListAccordingToSender($sender_id)
    {
        $beneficiaries = Beneficiary::join('sender_beneficiaries', 'sender_beneficiaries.beneficiary_id', '=', 'beneficiaries.beneficiary_id')
            ->join('person', 'person.id', '=', 'beneficiaries.person_id')
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
            ->leftJoin('beneficiary_bank_details', 'beneficiary_bank_details.beneficiaries_beneficiary_id', '=', 'beneficiaries.beneficiary_id')
            ->leftJoin('bank_details', 'bank_details.id', '=', 'beneficiary_bank_details.bank_details_id')
            ->where('beneficiary_bank_details.current', 1)
            ->where('sender_beneficiaries.sender_id', $sender_id)
            ->orderby('beneficiaries.beneficiary_id', 'desc')
            ->select(['beneficiaries.beneficiary_id', 'beneficiaries.added_by', 'person.first_name', 'person.last_name', 'phones.number', 'addresses.street','addresses.suburb','addresses.postcode','addresses.state','addresses.country_list_id', DB::raw('CONCAT_WS(" ", person.first_Name, NULLIF(person.middle_name,""), person.last_Name) AS full_name'), 'bank_details.account_no as accountNo','bank_details.account_name','bank_details.bsb', 'bank_details.bank_name as bankName'])
            ->get();
        return $beneficiaries;
    }

    /* DONE */
    public function add(array $request)
    {
        DB::beginTransaction();

        try {
            // Saving client profile
            $person = Person::create([
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name']
            ]);

            $beneficiary = Beneficiary::create([
                'person_id' => $person->id,
                'added_by' => current_user_id(),
                'created_date' => get_today_date()
            ]);
            DB::table('sender_beneficiaries')->insert([
                'sender_id' => $request['sender_id'],
                'beneficiary_id' => $beneficiary->beneficiary_id,
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

            // Add Bank Details
            $bank_details = BankDetails::create([
                'account_name' => $request['account_name'],
                'account_no' => $request['account_no'],
                'bsb' => $request['bsb'],
                'bank_name' => $request['bank_name'],
                'current' => 1
            ]);

            BeneficiaryBankDetails::create([
                'bank_details_id' => $bank_details->id,
                'beneficiaries_beneficiary_id' => $beneficiary->beneficiary_id,
                'current' => 1
            ]);

            DB::commit();
            return $beneficiary->beneficiary_id;
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
            ->select(['users.id as user_id', 'person.first_name', 'person.last_name', 'person.dob', 'addresses.*', 'users.email', 'phones.number', 'levels.name as role', 'levels.name as level_id', 'user_status.name as status', 'users.created_at', DB::raw('CONCAT_WS(" ", person.first_Name, NULLIF(person.middle_name,""), person.last_Name) AS full_name')])
            ->find($user_id);
        return $user;
    }

    public function edit(array $request, $id)
    {
        DB::beginTransaction();

        try {
            $beneficiary = Beneficiary::find($id);

            // Saving client profile
            $person = Person::find($beneficiary->person_id);
            $person->first_name = $request['first_name'];
            $person->last_name = $request['last_name'];
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

            }
            $person_address->address_id = $address->id;
            $person_address->save();

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
            $bank_detail = BeneficiaryBankDetails::where('beneficiaries_beneficiary_id', $beneficiary->beneficiary_id)->where('current', 1)->first();
            $bank_details = BankDetails::where('id', '=', $bank_detail->bank_details_id)->first();
            $bank_details->account_name = $request['account_name'];
            $bank_details->account_no = $request['account_no'];
            $bank_details->bsb = $request['bsb'];
            $bank_details->bank_name = $request['bank_name'];
            $bank_details->save();

            DB::commit();
            return $beneficiary->beneficiary_id;
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return false;
        }
    }

    public function person()
    {
        return $this->belongsTo(Person::class, 'id');

    }

    public function getBeneficiaryDetailById($id)
    {
        $beneficiaries = Beneficiary::join('person', 'person.id', '=', 'beneficiaries.person_id')
            ->leftJoin('person_phones', function ($q) {
                $q->on('person_phones.person_id', '=', 'person.id');
                $q->where('person_phones.current', '=', 1);
            })->leftJoin('person_address', function ($q) {
                $q->on('person_address.person_id', '=', 'person.id');
                $q->where('person_address.current', '=', 1);
            })
            ->leftJoin('phones', 'person_phones.phones_id', '=', 'phones.id')
            ->leftJoin('addresses', 'person_address.address_id', '=', 'addresses.id')
            ->leftJoin('country_list', 'country_list.id', '=', 'addresses.country_list_id')
            ->leftJoin('beneficiary_bank_details', 'beneficiary_bank_details.beneficiaries_beneficiary_id', '=', 'beneficiaries.beneficiary_id')
            ->leftJoin('bank_details', 'bank_details.id', '=', 'beneficiary_bank_details.bank_details_id')
            ->select(['bank_details.account_name', 'beneficiaries.created_date as dateAdded', 'bank_details.account_no as accountNo', 'bank_details.bsb', 'bank_details.bank_name as bankName', 'beneficiaries.beneficiary_id as beneficiary_id', 'country_list.name as country', 'beneficiaries.added_by', 'person.first_name', 'person.dob', 'person.last_name', 'addresses.*', 'person.email', 'phones.number', DB::raw('CONCAT_WS(" ", person.first_Name, NULLIF(person.middle_name,""), person.last_Name) AS full_name')])
            ->where('beneficiaries.beneficiary_id', $id)
            ->where('beneficiary_bank_details.current', 1)->first();

        return $beneficiaries;
    }

    public function getBeneficiaryBetailsByTransaction($transaction)
    {
        $beneficiaries = Beneficiary::join('person', 'person.id', '=', 'beneficiaries.person_id')
            ->leftJoin('person_phones', function ($q) {
                $q->on('person_phones.person_id', '=', 'person.id');
                $q->where('person_phones.current', '=', 1);
            })->leftJoin('person_address', function ($q) {
                $q->on('person_address.person_id', '=', 'person.id');
                $q->where('person_address.current', '=', 1);
            })
            ->leftJoin('phones', 'person_phones.phones_id', '=', 'phones.id')
            ->leftJoin('addresses', 'person_address.address_id', '=', 'addresses.id')
            ->leftJoin('country_list', 'country_list.id', '=', 'addresses.country_list_id')
            ->leftJoin('beneficiary_bank_details', 'beneficiary_bank_details.beneficiaries_beneficiary_id', '=', 'beneficiaries.beneficiary_id')
            ->leftJoin('bank_details', 'bank_details.id', '=', 'beneficiary_bank_details.bank_details_id')
            ->select(['bank_details.account_name as accountName', 'beneficiaries.created_date as dateAdded', 'bank_details.account_no as accountNo', 'bank_details.bsb', 'bank_details.bank_name as bankName', 'beneficiaries.beneficiary_id as beneficiary_id', 'country_list.name as country', 'beneficiaries.added_by', 'person.first_name', 'person.dob', 'person.last_name', 'addresses.*', 'person.email', 'phones.number', DB::raw('CONCAT_WS(" ", person.first_Name, NULLIF(person.middle_name,""), person.last_Name) AS full_name')])
            ->where('beneficiaries.beneficiary_id', $transaction->beneficiary_id)
            ->where('bank_details.id', $transaction->beneficiaries_bank_details_id)->first();

        return $beneficiaries;

    }

    public function deleteBeneficiary($beneficiary_id)
    {
        $beneficiary = Beneficiary::find($beneficiary_id);
        $person_id = $beneficiary->person_id;
        if (!empty($beneficiary)) {
            $person = Person::where('id', $beneficiary->person_id)->first();
            if ($person) {
                /* sender address delete */
                $person_address = PersonAddress::where('person_id', $person->id)->first();
                if ($person_address) {
                    Address::where('addresses.id', $person_address->address_id)->delete();
                    $person_address->delete();
                }
                /* beneficiary phone delete */
                $person_phone = PersonPhone::where('person_id', $person->id)->first();
                if ($person_phone) {
                    Phone::where('phones.id', $person_phone->phones_id)->delete();
                    $person_phone->delete();
                }
            }
            $beneficiary_bank_details = BeneficiaryBankDetails::where('beneficiaries_beneficiary_id', $beneficiary_id)->first();
            BankDetails::where('id', $beneficiary_bank_details->bank_details_id)->delete();
            $beneficiary_bank_details->delete();
            $sender_beneficiary_details = SenderBeneficiary::where('beneficiary_id', $beneficiary_id)->get();
            if ($sender_beneficiary_details->isNotEmpty()) {
                foreach ($sender_beneficiary_details as $key => $value) {

                    $a = SenderBeneficiary::where('beneficiary_id', $value->beneficiary_id)->first();

                    if ($a) {
                        $a->delete();
                    }
                }
            }
            $beneficiary->delete();
            Person::where('id', $person_id)->delete();
        }
return true;
    }
}

