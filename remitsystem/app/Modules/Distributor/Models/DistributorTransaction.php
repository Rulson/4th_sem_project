<?php

namespace App\Modules\Distributor\Models;

use App\Modules\Transaction\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DistributorTransaction extends Model
{
    //


    protected $table = 'distributor_transactions';
    protected $fillable = ['transaction_id', 'distributor_office_id', 'assigned_by', 'assigned_to', 'assigned_date', 'cost_rate'];
    public $timestamps = false;

 /*   public function totalSum($id)
    {
        $summary = $this->getSummary($id);

        $distrubutorTotal = 0;
        $paidForTransaction = 0;
        $payment_summary = [];
        $i = 0;
        $total = [];
        $data = 0;

        foreach ($summary as $summary) {

            if (isset($summary->transaction_id)) {
                $transaction = Transaction::join('transaction_details', 'transactions.transaction_details_id', '=', 'transaction_details.transaction_details_id')->where('transactions.id', $summary->transaction_id)->first();
                if ($transaction->transaction_status_id == 3 || $transaction->transaction_status_id == 4 || $transaction->transaction_status_id == 5) {
                    $payment_summary[$i]['date'] = standard_date($summary->created_at);
                    $payment_summary[$i]['balance'] = $transaction->payment_amount;
                    $payment_summary[$i]['description'] = format_id($summary->transaction_id, 'T') . ' Sender : ' . getSenderName($transaction->sender_id) . ' Beneficiary : ' . getBeneficiaryName($transaction->beneficiary_id);
                    $payment_summary[$i]['payment_amount'] = '';
                    $data = 0 - $payment_summary[$i]['balance'] + $data;
                    $payment_summary[$i]['t_balance'] = round($data, 2);
                    $paidForTransaction = round(($paidForTransaction + $transaction->payment_amount), 2);
                }
            } else {
                $payment_summary[$i]['date'] = $summary->date;
                $payment_summary[$i]['description'] = $summary->description;
                $payment_summary[$i]['balance'] = '';
                $payment_summary[$i]['payment_amount'] = $summary->amount;
                $data = $payment_summary[$i]['payment_amount'] - 0 + $data;
                $payment_summary[$i]['t_balance'] = round($data, 2);

                $distrubutorTotal = round(($distrubutorTotal + $summary->amount), 2);
            }
            $i++;
        }
        $outstandingBalance = round(($distrubutorTotal - $paidForTransaction), 2);
        $total['distributorTotal'] = $distrubutorTotal;
        $total['paidForTransaction'] = $paidForTransaction;
        $total['outstandingBalance'] = $outstandingBalance;
        $total['paymentSummary'] = $payment_summary;
        return $total;
    }*/

    public function paymentSummaryById($id)
    {
        $summary = $this->getSummary($id);

        $distrubutorTotal = 0;
        $paidForTransaction = 0;
        $payment_summary = [];
        $i = 0;
        $total = [];
        $data = 0;

        foreach ($summary as $summary) {

            if (isset($summary->transaction_id)) {
                $transaction = Transaction::join('transaction_details', 'transactions.transaction_details_id', '=', 'transaction_details.transaction_details_id')->where('transactions.id', $summary->transaction_id)->first();
                $assign_distributor = DistributorsAssign::where('transactions_id', $summary->transaction_id)->where('distributor_office_id', $id)->first();
                $payment_summary[$i]['date'] = standard_date($summary->created_at);
                $payment_summary[$i]['balance'] = round(($assign_distributor->amount / $transaction->cost_rate), 2);
                $first_operand = round($assign_distributor->amount / $transaction->exchange_rate, 2);
                $second_operand = $transaction->cost_rate;
                $multiple = round($first_operand * $second_operand, 2);
                $payment_summary[$i]['description'] = format_id($summary->transaction_id, 'T') . ' Sender : ' . getSenderName($transaction->sender_id) . ' Beneficiary : ' . getBeneficiaryName($transaction->beneficiary_id) . "\n\n" . ' [' . $first_operand . '*' . $second_operand . ' = ' .' NPR '. $multiple. ']' /*. ' (AUD ' . $distributor_final_amount . ')]'*/;
                $payment_summary[$i]['payment_amount'] = '';
                $data = 0 - $payment_summary[$i]['balance'] + $data;
                $payment_summary[$i]['t_balance'] = round($data, 2);
                $paidForTransaction = round(($paidForTransaction + round(($assign_distributor->amount / $transaction->cost_rate), 2)), 2);
            } else {
                $payment_summary[$i]['date'] = standard_date($summary->date);
                $payment_summary[$i]['description'] = $summary->description.' @ '.$summary->distributor_cost_rate;
                $payment_summary[$i]['balance'] = '';
                $payment_summary[$i]['payment_amount'] = $summary->amount;
                $data = $payment_summary[$i]['payment_amount'] - 0 + $data;
                $payment_summary[$i]['t_balance'] = round($data, 2);

                $distrubutorTotal = round(($distrubutorTotal + $summary->amount), 2);
            }
            $i++;
        }
        $outstandingBalance = round(($distrubutorTotal - $paidForTransaction), 2);
        $total['distributorTotal'] = $distrubutorTotal;
        $total['paidForTransaction'] = $paidForTransaction;
        $total['outstandingBalance'] = $outstandingBalance;
        $total['paymentSummary'] = $payment_summary;
        return $total;
    }

    public function getSummary($id)
    {
        $distributorOffice = DistributorOffice::where('companies_id', $id)->first();
        $summary = DistributorAccount::leftJoin('distributor_payments', 'distributor_payments.id', '=', 'distributor_accounts.distributor_payments_id')
            ->leftJoin('distributor_transactions', 'distributor_transactions.id', '=', 'distributor_accounts.distributor_transactions_id')
            ->where('distributor_transactions.distributor_office_id', $distributorOffice->id)
            ->orwhere('distributor_payments.distributor_company_id', $id)
            ->select('distributor_accounts.*', 'distributor_payments.amount','distributor_payments.cost_rate as distributor_cost_rate', 'distributor_payments.description', 'distributor_payments.date as date', 'distributor_transactions.*')
            ->orderby('distributor_accounts.id', 'asc')->get();
        return $summary;
    }

    public function getDistributorTransaction($company_id)
    {
        $distributor_transaction = DistributorsAssign::join('transactions', 'transactions.id', '=', 'assign_distributors.transactions_id')
            ->leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
            ->leftJoin('distributor_offices', 'distributor_offices.id', '=', 'assign_distributors.distributor_office_id')
            ->join('status', 'status.id', '=', 'transactions.transaction_status_id')
            ->where('distributor_offices.companies_id', $company_id)
            ->select('transactions.*', 'transaction_details.*', 'status.name','assign_distributors.amount as distributor_amount')
            ->orderBy('transactions.id', 'desc');
        return $distributor_transaction;
    }

}
