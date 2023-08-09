<?php

namespace App\Repositories;

use App\Models\MonthlyPayment;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MonthlyPaymentRepository
{
    public function add($data){
        MonthlyPayment::insert($data);
    }

    public function getPayment($id){
        return MonthlyPayment::find($id);
    }

    public function getPaymentsByDate($date){
        return MonthlyPayment::with('student','attach')->where('date', $date)->latest()->get();
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

    public function monthPaymentsBySubjectId($subject_id){
        $payments = MonthlyPayment::where('subject_id', $subject_id)
            ->select('month', DB::raw('SUM(amount_paid) as total'),DB::raw('SUM(amount) as debt'))
            ->groupBy('month')
            ->get();


        return $payments;
    }

    public function getPaymentsByMonth($subject_id){
        $payments_success = MonthlyPayment::where('subject_id', $subject_id)
            ->where('amount_paid','>',0)
            ->select('month', DB::raw('SUM(amount_paid) as total'))
            ->groupBy('month')
            ->get();



        return $payments_success;
    }
}
