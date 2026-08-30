<?php

namespace App\Modules\Transaction\Service;

use App\Modules\Transaction\Constants\TransactionConstant;
use App\Modules\Transaction\Models\Transaction;
use Illuminate\Support\Collection;

class TransactionCalculationService  {
    public function getTodaysTransactionForClient($sender_id){
        return  Transaction::where('sender_id', $sender_id)
            ->join('transaction_details', 'transactions.transaction_details_id', '=', 'transaction_details.transaction_details_id')
            ->whereDate('transactions.created_at', date('Y-m-d'))
            ->get();
    }

    public function getTodaysTransactionForAgent($agentId){
          return Transaction::join('agent_transactions', 'transactions.id', '=', 'agent_transactions.transactions_id')
            ->where('agent_transactions.agents_id', $agentId)
            ->join('transaction_details', 'transactions.transaction_details_id', '=', 'transaction_details.transaction_details_id')
            ->whereDate('transactions.created_at', date('Y-m-d'))
            ->get();
    }
    /**
     * @param Collection $transactions
     * @return void
     */
    public function totalSentAmount(Collection $transactions): float {
       return $transactions->sum('sending_amount');
    }
    public function getRemainingLimitAmountForClient($senderId) : float{
        $todaysTransactions= $this->getTodaysTransactionForClient($senderId);
        $totalAmountSent = $this->totalSentAmount($todaysTransactions);
        return TransactionConstant::DAILY_LIMIT - $totalAmountSent;
    }
    public function getRemainingLimitAmountForAgent($agentId){
        $todaysTransactions= $this->getTodaysTransactionForAgent($agentId);
        $totalAmountSent = $this->totalSentAmount($todaysTransactions);
        return TransactionConstant::DAILY_LIMIT - $totalAmountSent;
    }
}
