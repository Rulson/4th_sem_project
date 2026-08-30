<?php

namespace App\Modules\Sender\Models;

use App\Modules\Agent\Models\Agent;
use App\Modules\Distributor\Models\DistributorUser;
use App\Modules\Sender\Models\Company;
use App\Modules\User\Models\Address;
use App\Modules\User\Models\AusStates;
use App\Modules\User\Models\Person;
use App\Modules\User\Models\PersonAddress;
use App\Modules\User\Models\PersonPhone;
use App\Modules\User\Models\Phone;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Modules\Sender\Models\IdentificationDocument;

class Sender extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'senders';

    /**
     * The primary key of the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'person_id', 'added_by', 'sender_status_id', 'created_at', 'updated_at', 'service_charge','similar_ids'
    ];

    /**
     * Disable Laravel's Eloquent timestamps
     */
    public $timestamps = false;


    private $error;


    public function validate( array $data,$agent_id)
    {
        $sender = Sender::leftjoin('person','person.id','=','senders.person_id')
            ->where('person.email',$data['email'])
            ->where('added_by',$agent_id)->first();

        if(!$sender){
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
    public function person()
    {
        return $this->belongsTo(Person::class, 'id');

    }

    public function getDistributorSender()
    {
        $senders = Sender::join('person', 'person.id', '=', 'senders.person_id');

    }

    public function getAll()
    {
        $senders = Sender::join('person', 'person.id', '=', 'senders.person_id')
            ->join('sender_status', 'senders.sender_status_id', '=', 'sender_status.id')
            ->leftJoin('person_phones', function ($q) {
                $q->on('person_phones.person_id', '=', 'person.id');
                $q->where('person_phones.current', '=', 1);
            })
            ->leftJoin('phones', 'person_phones.phones_id', '=', 'phones.id')
            ->select(['senders.id as sender_id', 'senders.created_at as dateAdded', 'senders.added_by', 'person.first_name', 'person.last_name', 'person.email', 'senders.sender_status_id', 'phones.number', 'sender_status.name as status', DB::raw('CONCAT_WS(" ", person.first_Name, NULLIF(person.middle_name,""), person.last_Name) AS full_name')])
            ->orderBy('senders.id', 'desc');
        /*temporary added for loading issue staff and admin case*/
        /*temporary code*/
        if (Auth::user()->level_id == 3) {
            $senders = $senders->where('senders.added_by', Auth::user()->id);
        }
        if (Auth::user()->level_id == 5) {
            $senders = $senders->leftJoin('users', 'users.person_id', '=', 'senders.person_id')->where('users.id', Auth::user()->id);
        }
        if (Auth::user()->level_id == 4) {
            $distributor_staff = DistributorUser::where('user_id', Auth::user()->id)->first();
            if ($distributor_staff->role_id == 1) {
                $senders = $senders->join('transactions', 'transactions.sender_id', '=', 'senders.id')
                    ->join('distributor_transactions', 'distributor_transactions.transaction_id', '=', 'transactions.id')
                    ->where('distributor_transactions.distributor_office_id', $distributor_staff->distributor_office_id)
                    ->distinct();
            } else {
                $senders = $senders->join('transactions', 'transactions.sender_id', '=', 'senders.id')
                    ->join('distributor_transactions', 'distributor_transactions.transaction_id', '=', 'transactions.id')
                    ->where('distributor_transactions.assigned_to', Auth::user()->id)
                    ->distinct();

            }
        }

        return $senders->get();
    }

    public function add(array $request)
    {
        DB::beginTransaction();

        try {
            // Saving client profile
            $person = Person::create([
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name'],
//                'dob' => insert_dateformat($request['dob']),
                'dob' => $request['dob'],
                'email' => strtolower($request['email'])
            ]);
            if (Auth::user()->level_id == 1 || Auth::user()->level_id == 2) {
                $sender = Sender::create([
                    'person_id' => $person->id,
                    'added_by' => current_user_id(),
                    'sender_status_id' => 2,
                    'service_charge' => !empty($request['service_charge']) ? $request['service_charge'] : null
                ]);

            } else {
                $sender = Sender::create([
                    'person_id' => $person->id,
                    'added_by' => current_user_id(),
                    'sender_status_id' => 2,
                ]);
            }

            if (Auth::user()->level_id == 3) {
                $agent = Agent::where('user_id', Auth::user()->id)->first();
                DB::table('agent_senders')->insert([
                    'agents_id' => $agent->id,
                    'senders_sender_id' => $sender->id
                ]);
            }
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
            $identification_document_id = $this->uploadIdentification($request, $person->id);
            // Add identification
            $identification = Identification::create([
                'issued_by' => $request['issued_by'],
                'id_number' => $request['id_number'],
                'identification_status_id' => 2, //later
                'identification_types_id' => $request['id_type'],
                'state' => $request['state'],
//                'expiry_date' => insert_dateformat($request['expiry_date']),
                'expiry_date' => $request['expiry_date'],
                'senders_id' => $sender->id,
                'identification_documents_id' => $identification_document_id,
                'current' => 1
            ]);

            /* Only if company */
            /*  if ($request['company_name'] != '') {
                  $company = Company::create([
                      'company_name' => $request['company_name'],
                      'abn' => $request['abn'],
                      'addresses_id' => $address->id
                  ]);

                  SenderCompany::create([
                      'senders_sender_id' => $sender->id,
                      'companies_id' => $company->id,
                      'active' => 1
                  ]);
              }*/
            $suburb = AusStates::where('name', 'like', '%' .$request['suburb'] . '%')->where('type','aus_suburb')->first();
            if(!$suburb){
                $parent_id = AusStates::select('id')->where('name',$request['state'])->first()->id;
                AusStates::create(['name'=>ucwords($request['suburb']),'type'=>'aus_suburb','parent_id'=>$parent_id,'postcode'=>$request['postcode']]);
            }

            DB::commit();
            return $sender->id;
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return false;
        }
    }

    public function uploadIdentification($request, $id = 0)
    {
        if (isset($request['image'])) {
            $file = $request['image'];
        }
        if (isset($request['image1'])) {
            $file1 = $request['image1'];
        }
        if(isset($request['address_proof'])){
            $file2 = $request['address_proof'];
        }

        if (\Illuminate\Support\Facades\Request::hasfile('image')) {
            $destinationPath = 'identification';
            $fileName = date('Y-m-d-H-i-s') . $file->getClientOriginalName();
            $file->move($destinationPath, $fileName);

            $document = Document::create([
                'type' => '',
                'user_id' => Auth::user()->id,
                'name' => $fileName,
                'shelf_location' => '',
            ]);

            if(\Illuminate\Support\Facades\Request::hasfile('image1')){
                $fileName1 = date('Y-m-d-H-i-s') . $file1->getClientOriginalName();
                $file1->move($destinationPath, $fileName1);

                $document->name1 = $fileName1;
                $document->save();
            }
            if(isset($file2) && \Illuminate\Support\Facades\Request::hasfile('address_proof')){
                $fileName2 = date('Y-m-d-H-i-s') . $file2->getClientOriginalName();
                $file2->move($destinationPath, $fileName2);

                $document->address_proof = $fileName2;
                $document->save();
            }


            $identification_document = IdentificationDocument::create([
                'document_id' => $document->id,
            ]);
            return ($identification_document->id);
        } else {
            $document = Document::create([
                'type' => '',
                'user_id' => Auth::user()->id,
                'name' => 'noId.jpg',
                'shelf_location' => '',
            ]);

            $identification_document = IdentificationDocument::create([
                'document_id' => $document->id,
            ]);
            return ($identification_document->id);
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

    public function getSenderDetailById($id)
    {

        $senders = Sender::join('person', 'person.id', '=', 'senders.person_id')
            ->join('sender_status', 'senders.sender_status_id', '=', 'sender_status.id')
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
            ->leftJoin('identifications', 'identifications.senders_id', '=', 'senders.id')
            ->leftJoin('identification_status', 'identification_status.id', '=', 'identifications.Identification_status_id')
            ->leftJoin('identification_types', 'identification_types.id', '=', 'identifications.identification_types_id')
            ->select(['senders.service_charge as service_charge', 'senders.added_by as added_by','senders.person_id as sender_person_id', 'identification_status.name as identificationStatus', 'identifications.expiry_date', 'identifications.issued_by', 'identifications.id_number', 'identification_types.name', 'identifications.identification_types_id AS id_type', 'senders.id as sender_id', 'country_list.name as country', 'senders.added_by', 'person.first_name', 'person.dob', 'person.last_name', 'addresses.*', 'person.email', 'senders.sender_status_id', 'phones.number', 'senders.created_at as dateAdded', 'sender_status.name as status', DB::raw('CONCAT_WS(" ", person.first_Name, NULLIF(person.middle_name,""), person.last_Name) AS full_name')])
            ->where('senders.id', $id)
            ->where('identifications.current', 1)
            ->orderBy('identifications.identification_id', 'desc')
            /*  ->where('identifications.Identification_status_id',2)
            */
            ->first();


        return $senders;

    }

    public function edit(array $request, $sender_id)
    {
        DB::beginTransaction();

        try {
            $sender = Sender::find($sender_id);
            if (!empty($request['service_charge'])) {
                $sender1 = Sender::find($sender_id);
                $sender1->service_charge = $request['service_charge'];
                $sender1->save();
            }
            $check_if_admin = User::where('id',$request['added_by'])->where('level_id',1)->first();
            if($check_if_admin){
                AgentSender::where('senders_sender_id',$sender_id)->delete();
            }
            $check_if_agent = User::where('id', $request['added_by'])->where('level_id', 3)->first();
            if ($check_if_agent) {
                $agent = Agent::where('user_id', $request['added_by'])->first();
                $agent_sender = AgentSender::where('senders_sender_id', $sender_id)->first();
                if (!$agent_sender) {
                    AgentSender::create([
                        'agents_id' => $agent->id,
                        'senders_sender_id' => $sender_id,
                    ]);
                }else{
                    AgentSender::where('senders_sender_id', $sender_id)->update([
                        'agents_id' => $agent->id,
                    ]);
                }
            }
            $sender->added_by = $request['added_by'];
            $sender->save();


            // Saving client profile
            $person = Person::find($sender->person_id);
            $person->first_name = $request['first_name'];
            $person->last_name = $request['last_name'];
            $person->email = strtolower($request['email']);
            $person->dob =insert_dateformat($request['dob']);
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
            //Image Update
            $identification = Identification::where('senders_id', $sender_id)->update([
                'issued_by' => $request['issued_by'],
                'issued_by' => $request['issued_by'],
                'id_number' => $request['id_number'],
                'identification_types_id' => $request['id_type'],
                'expiry_date' => insert_dateformat($request['expiry_date']),
            ]);
            if (\Illuminate\Support\Facades\Request::hasfile('image')) {
                $file = $request['image'];
                $destinationPath = 'identification';
                $fileName = date('Y-m-d-H-i-s') . $file->getClientOriginalName();
                $file->move($destinationPath, $fileName);

                $data = DB::table('senders')
                    ->join('identifications', 'senders.id', '=', 'identifications.senders_id')
                    ->join('identification_documents', 'identifications.identification_documents_id', '=', 'identification_documents.id')
                    ->join('documents', 'identification_documents.document_id', '=', 'documents.id')
                    ->where('senders.id', $sender_id)
                    ->update([
                        'documents.name' => $fileName,
                    ]);
            }
            $suburb = AusStates::where('name', 'like', '%' .$request['suburb'] . '%')->where('type','aus_suburb')->first();
            if(!$suburb){
                $parent_id = AusStates::select('id')->where('name',$request['state'])->first()->id;
                AusStates::create(['name'=>ucwords($request['suburb']),'type'=>'aus_suburb','parent_id'=>$parent_id,'postcode'=>$request['postcode']]);
            }
            DB::commit();
            return $sender->id;
            // all good

        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return false;
        }
    }
    public function getAllSenderByAddBy($id)
    {
        $senders = Sender::join('person', 'person.id', '=', 'senders.person_id')
            ->join('sender_status', 'senders.sender_status_id', '=', 'sender_status.id')
            ->leftJoin('person_phones', function ($q) {
                $q->on('person_phones.person_id', '=', 'person.id');
                $q->where('person_phones.current', '=', 1);
            })
            ->leftJoin('phones', 'person_phones.phones_id', '=', 'phones.id')
            ->select(['senders.id as sender_id', 'senders.created_at as dateAdded', 'senders.added_by', 'person.first_name', 'person.last_name', 'person.email', 'senders.sender_status_id', 'phones.number', 'sender_status.name as status', DB::raw('CONCAT_WS(" ", person.first_Name, NULLIF(person.middle_name,""), person.last_Name) AS full_name')])
            ->where('senders.added_by', $id)
            ->orderBy('senders.id', 'desc')->get();

        return $senders;
    }

    public function checkSimilarity( array $data, $sender_id,$application_id){
        $query = Sender::join('person', 'person.id', '=', 'senders.person_id')
            ->leftJoin('person_phones', function ($q) {
                $q->on('person_phones.person_id', '=', 'person.id');
                $q->where('person_phones.current', '=', 1);
            })
            ->leftJoin('phones', 'person_phones.phones_id', '=', 'phones.id')
            ->leftJoin('users','users.person_id','=','person.id')
            ->where('senders.id','!=',$sender_id)
            ->where('users.application_id',$application_id)
            ->select(['senders.id','person.first_name', 'person.last_name', 'phones.number','person.dob']);


          $query = $query->where(function ($query) use ($data) {
              $phone = $data['phone_number'];
                $query->where(function($q) use ($data){
                    $first_name = $data['first_name'];
                    $last_name = $data['last_name'];
                    $dob = insert_dateformat($data['dob']);
                    $q->where("person.first_name","=",$first_name)
                        ->where("person.last_name","=",$last_name)
                        ->where("person.dob","=",$dob);
                })->orWhere("phones.number","=",$phone);
            })

              ->get();

        if($query->count() > 0){
            $Array= [];

            foreach($query as $sender){
                $Array[] = $sender->id;
            }
            $ids = implode(', ', $Array);
            $this->where('id',$sender_id)->update([
                'similar_ids' => $ids
            ]);
        }

        return true;

    }

    public function scopeUserId($query,$sender_id){
        $user = $query->join('person','person.id','=','senders.person_id')
            ->join('users','users.person_id','=','person.id')
            ->where('senders.id',$sender_id)
            ->select('users.id')
            ->first();
        return !empty($user) ? $user->id: null;
    }

    public function getSendersEmailByAddBy($id){
        $senders = Sender::join('person', 'person.id', '=', 'senders.person_id')
            ->leftJoin('users','users.person_id','=','person.id')
            ->where('senders.added_by', $id)
            ->select(['person.email','users.application_id'])
            ->get();

        return $senders;

    }

    public function getSendersEmail()
    {
        $senders = Sender::join('person', 'person.id', '=', 'senders.person_id')
            ->leftJoin('users','users.person_id','=','person.id')
            ->select(['person.email','users.application_id'])
            ->get();

        return $senders;
    }
}

