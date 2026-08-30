<?php

namespace App\Modules\Api\Controllers;

use App\Http\Controllers\BaseController;
use App\Modules\Beneficiary\Models\BankDetails;
use App\Modules\Beneficiary\Models\Beneficiary;
use App\Modules\Beneficiary\Models\BeneficiaryBankDetails;
use App\Modules\Sender\Models\Identification;
use App\Modules\Sender\Models\Sender;
use App\Modules\Transaction\Models\Transaction;
use App\Modules\Transaction\Models\TransactionDetails;
use App\Modules\Transaction\Models\TransactionDocument;
use App\Modules\User\Models\Address;
use App\Modules\User\Models\Person;
use App\Modules\User\Models\PersonAddress;
use App\Modules\User\Models\PersonPhone;
use App\Modules\User\Models\Phone;
use App\Modules\User\Models\User;
use App\Rules\MaxWords;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BeneficiaryController extends ApiController
{
    function __construct(Beneficiary $beneficiary)
    {
        $this->beneficiary = $beneficiary;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if ($request) {
            $access_token = $request->header('X-Access-Token');
            if ($access_token) {
                $user = User::where('api_token', $access_token)->where('level_id', 5)->first();
                if ($user) {
                    $sender = Sender::where('person_id', $user->person_id)->first();
                    if ($sender) {
                        $beneficiaries = $this->beneficiary->getBeneficiaryListAccordingToSender($sender->id);
                        foreach ($beneficiaries as $beneficiary) {
                            $beneficiary->district = $beneficiary->suburb;
                            $beneficiary->suburb = $beneficiary->street;
                            unset($beneficiary->street);
                        }
                    } else {
                        return response()->json(['message' => 'Sender not found', 'status' => 404]);
                    }
                    if ($beneficiaries->count() > 0) {
                        return response()->json(['response' => $beneficiaries, 'message' => 'Success', 'status' => 200]);
                    } else {
                        return response()->json(['response' => $beneficiaries, 'message' => 'No beneficiary add.', 'status' => 200]);
                    }

                } else {
                    return response()->json(['message' => 'User not found', 'status' => 404]);
                }
            } else {
                return response()->json(['message' => 'Access token is not set.', 'status' => 404]);
            }
        }
        return response()->json(['message' => 'Failed', 'status' => 404]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $access_token = $request->header('X-Access-Token');
        if (!$access_token) {
            return response()->json(['message' => 'Access Token is not set', 'status' => 404]);
        }
        $messages = [
            'number.regex' => 'The phone number format is invalid. It should be a valid nepali number in the format 9XXXXXXXXX or 01-XXXXXXX',
        ];
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:2|max:55',
            'last_name' => 'required|min:2|max:55',
            'number' => ['required', 'regex:/^(?:\+977[-\s]?)?[9][6-9]\d{8}$|^01[-]?[0-9]{7}$/'],
            'suburb' => 'required',
            'district' => 'required',
            'bsb' => ['required', new MaxWords(2)],
            'state' => 'required',
            'postcode' => 'required',
            'country_list_id' => 'required',
        ], $messages);
        if ($validator->fails()) {
            $validationErrorMessage = implode(",",$validator->messages()->all());
            return response()->json(['response' => $validator->errors(), 'message' => $validationErrorMessage, 'status' => 422]);
        }
        $user = User::where('api_token', $access_token)->where('level_id', 5)->first();
        if ($user) {
            $sender = Sender::where('person_id', $user->person_id)->first();
            if ($sender) {
                $request->request->add(['sender_id' => $sender->id]);
                $this->addBeneficiary($request->all(), $user);
                return response()->json(['message' => 'Success', 'status' => 200]);
            } else {
                return response()->json(['message' => 'Sender no found.', 'status' => 404]);
            }

        } else {
            return response()->json(['message' => 'User not found', 'status' => 404]);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $access_token = $request->header('X-Access-Token');

        if ($access_token) {
            $user = User::where('api_token', $access_token)->where('level_id', 5)->first();
            if ($user) {
                $beneficiaries = $this->beneficiary->getBeneficiaryDetailById($request->id);
                if ($beneficiaries) {
                    $beneficiaries->district = $beneficiaries->suburb;
                    $beneficiaries->suburb = $beneficiaries->street;
                    unset($beneficiaries->street);
                    return response()->json(['response' => $beneficiaries, 'message' => 'Success', 'status' => 200]);
                } else {
                    return response()->json(['message' => 'Beneficiary not found.', 'status' => 404]);
                }
            } else {
                return response()->json(['message' => 'User not found', 'status' => 404]);
            }
        } else {
            return response()->json(['message' => 'Access token is not set', 'status' => 404]);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $beneficiary = Beneficiary::find($id);

        if ($beneficiary) {
            $messages = [
                'number.regex' => 'The phone number format is invalid. It should be a valid nepali number in the format 9XXXXXXXXX or 01-XXXXXXX',
            ];
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|min:2|max:55',
                'last_name' => 'required|min:2|max:55',
                'number' => ['required', 'regex:/^(?:\+977[-\s]?)?[9][6-9]\d{8}$|^01[-]?[0-9]{7}$/'],
                'suburb' => 'required',
                'district' => 'required',
                'bsb' => ['required', new MaxWords(2)],
                'state' => 'required',
                'postcode' => 'required',
                'country_list_id' => 'required',
                'phone_number' => 'required',
                'account_name' => 'required',
            ], $messages);
            if ($validator->fails()) {
                $validationErrorMessage = implode(",",$validator->messages()->all());
                return response()->json(['response' => $validator->errors(), 'message' => $validationErrorMessage, 'status' => 422]);
            }

            DB::beginTransaction();

            try {
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
                    $address->street = $request['suburb'];
                    $address->suburb = $request['district'];
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
                $bank_detail = BeneficiaryBankDetails::where('beneficiaries_beneficiary_id', $beneficiary->beneficiary_id)->where('current', 1)->first();
                $bank_details = BankDetails::where('id', '=', $bank_detail->bank_details_id)->first();
                $bank_details->account_name = $request['account_name'];
                $bank_details->account_no = $request['account_no'];
                $bank_details->bsb = $request['bsb'];
                $bank_details->bank_name = $request['bank_name'];
                $bank_details->save();

                DB::commit();
                return response()->json(['message' => 'Beneficiary updated.', 'status' => 200]);
                // all good
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => 'Beneficiary could not update.', 'status' => 401]);
            }

        } else {
            return response()->json(['message' => 'Beneficiary not found.', 'status' => 404]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $this->beneficiary->deleteBeneficiary($id);
        return response()->json(['message' => 'Success', 'status' => 200]);
    }

}
