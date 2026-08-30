<?php

namespace App\Imports;

use App\Modules\Sender\Models\Identification;
use App\Modules\Sender\Models\Sender;
use App\Modules\User\Models\Address;
use App\Modules\User\Models\Person;
use App\Modules\User\Models\PersonAddress;
use App\Modules\User\Models\PersonPhone;
use App\Modules\User\Models\Phone;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;


class SenderImports implements ToModel, WithBatchInserts, WithChunkReading
{
    use Importable;

    /**
     * @param Collection $collection
     */

    public function model(array $rows)
    {
        if (isset($rows[0]) && isset($rows[9]) && isset($rows[8])) {
            $sender1 = Sender::join('person', 'person.id', '=', 'senders.person_id')->where('person.email', $rows[8])->where('senders.added_by', Auth::user()->id)->first();
          if (!$sender1) {
                $person = Person::create([
                    'first_name' => $rows[0],
                    'last_name' => $rows[1],
                    'dob' =>$rows[9],
                    'email' => isset($rows[8]) ? strtolower($rows[8]) : ''
                ]);
                $sender = Sender::create([
                    'person_id' => $person->id,
                    'added_by' => current_user_id(),
                    'sender_status_id' => 1 //Change this later
                ]);

                $address = Address::create([
                    'street' => isset($rows[3]) ? $rows[3] : '',
                    'suburb' => isset($rows[4]) ? $rows[4] : '',
                    'postcode' => isset($rows[6]) ? $rows[6] : '',
                    'state' => isset($rows[5]) ? $rows[5] : '',
                    'country_list_id' => 13,
                ]);

                PersonAddress::create([
                    'address_id' => $address->id,
                    'person_id' => $person->id,
                    'current' => 1,
                    'address_status_id' => 1
                ]);
                
                $phone = new Phone();
                $phone_id = $phone->add($rows[2]);
                PersonPhone::create([
                    'phones_id' => $phone_id,
                    'person_id' => $person->id,
                    'current' => 1
                ]);
                $senderInstance = new Sender();
                $identification_document_id = $senderInstance->uploadIdentification($rows, $person->id);
                $identification = new Identification();
                $identification->issued_by = isset($rows[12]) ? $rows[12] : '';
                $identification->id_number = isset($rows[11]) ? $rows[11] : '';
                $identification->identification_status_id = 2;
                if (strtolower($rows[10]) == "passport") {
                    $identification->identification_types_id = 1;
                } else if (strtolower($rows[10]) == "photo id") {
                    $identification->identification_types_id = 3;
                } else if (strtolower($rows[10]) == "driver's licence" || strtolower($rows[10]) == "driver's license") {
                    $identification->identification_types_id = 2;
                } else {
                    $identification->identification_types_id = 4;
                }
                $identification->expiry_date = '2025-01-01';
                $identification->senders_id = $sender->id;
                $identification->identification_documents_id = $identification_document_id;
                $identification->current = 1;
                $identification->save();
            }
        }
    }

    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 1000;

    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

}
