<?php

namespace App\Modules\Transaction\Models;

use App\Modules\Sender\Models\Document;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class TransactionDocument extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'transaction_documents';

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
        'document_name', 'file_name', 'created_date', 'added_by', 'transaction_document_type_id', 'transactions_id'
    ];

    /**
     * Disable Laravel's Eloquent timestamps
     */
    public $timestamps = false;

    public function multipleReceiptUpload($receipt,$transaction_id){

        if (\Illuminate\Support\Facades\Request::file('receipt')) {

            foreach ($receipt['receipt'] as $image) {
                $destinationPath = 'TransactionIdentification';
                $uniqueid = uniqid();
                $fileName = date('Y-m-d-H-i-s') . '-' . $uniqueid . '.' . $image->getClientOriginalExtension();

                $image->move($destinationPath, $fileName);
               TransactionDocument::create([
                    'file_name'=> $fileName,
                    'transaction_document_type_id' => 1,
                    'transactions_id' => $transaction_id,
                    'added_by' => Auth::user()->id,
                    'created_date' => Carbon::today()
                ]);
                Document::create([
                    'type'=>'',
                    'user_id' => Auth::user()->id,
                    'name' => $fileName,
                    'shelf_location'=>'',
                ]);
            }
        }else{
        TransactionDocument::create([
                'file_name'=> 'noReceiptUploaded.jpg',
                'transaction_document_type_id' => 1,
                'transactions_id' => $transaction_id,
                'added_by' => Auth::user()->id,
                'created_date' => Carbon::today()
            ]);
            Document::create([
                'type'=>'',
                'user_id' => Auth::user()->id,
                'name' => 'noReceiptUploaded.jpg',
                'shelf_location'=>'',
            ]);
       }
    }
}

