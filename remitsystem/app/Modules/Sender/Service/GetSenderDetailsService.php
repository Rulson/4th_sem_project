<?php


namespace App\Modules\Sender\Service;

use App\Modules\Sender\Models\Sender;
use Illuminate\Support\Facades\DB;

class GetSenderDetailsService {
    public function getSenderDetails($id)
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
            ->leftJoin('identification_types', 'identification_types.id', '=', 'identifications.identification_types_id')
            ->select(['identifications.expiry_date', 'identifications.issued_by', 'identifications.id_number',
                'identification_types.name', 'identifications.identification_types_id AS id_type', 'senders.id as sender_id',
                'country_list.name as country', 'senders.added_by', 'person.first_name', 'person.dob', 'person.last_name',
                'addresses.*', 'person.email', 'senders.sender_status_id', 'phones.number', 'sender_status.name as status',
                DB::raw('CONCAT_WS(" ", person.first_Name, NULLIF(person.middle_name,""), person.last_Name) AS full_name')])
            ->where('senders.id', $id)->first();
        return $senders;
    }
}
