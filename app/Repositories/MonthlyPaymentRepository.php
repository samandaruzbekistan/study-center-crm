<?php

namespace App\Repositories;

use App\Models\MonthlyPayment;
use Carbon\Carbon;

class MonthlyPaymentRepository
{
    public function add($data){
        MonthlyPayment::insert($data);
    }

    public function getPayment($id){
        return MonthlyPayment::find($id);
    }

    public function getPaymentsByDate($date){
        return MonthlyPayment::with('student')->where('date', $date)->latest()->get();
    }

    public function payment($id,$amount,$amount_paid,$type,$status){
        $currentDateTime = Carbon::now('Asia/Tashkent');
        MonthlyPayment::where('id', $id)
            ->update([
                'amount' => $amount,
                'amount_paid' => $amount_paid,
                'type' => $type,
                'status' => $status,
                'date' => date('Y-m-d'),
                'paid_cashier_id' => session('id'),
                'created_at' => $currentDateTime
            ]);
    }
}
