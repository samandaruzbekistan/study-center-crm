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

    public function getPayments(){
        return MonthlyPayment::query()
            ->with(['student' => function ($query) {
                $query->select('id', 'name');
            }, 'teacher' => function ($query) {
                $query->select('id', 'name');
            }, 'subject' => function ($query) {
                $query->select('id', 'name');
            }])->where('date','!=', null)->orderBy('date', 'desc')->paginate(100);
    }

    public function getPayments7(){
        return MonthlyPayment::query()
            ->with(['student' => function ($query) {
                $query->select('id', 'name');
            }, 'teacher' => function ($query) {
                $query->select('id', 'name');
            }, 'subject' => function ($query) {
                $query->select('id', 'name');
            }])->where('date','!=', null)->orderBy('date', 'desc')->paginate(7);
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
            ->select('month','subject_id', DB::raw('SUM(amount_paid) as total'),DB::raw('SUM(amount) as debt'))
            ->groupBy('month')
            ->get();

        return $payments;
    }

    public function monthPaymentsByDateOrderType($date){
        $payments = MonthlyPayment::where('date', $date)
            ->select('type', DB::raw('SUM(amount_paid) as total'))
            ->groupBy('type')
            ->get();

        return $payments;
    }

    public function getPaidPaymentsByMonth($subject_id){
        $payments_success = MonthlyPayment::where('subject_id', $subject_id)
            ->where('amount_paid','>',0)
            ->select('month', DB::raw('SUM(amount_paid) as total'))
            ->groupBy('month')
            ->get();



        return $payments_success;
    }

    public function getPaymentsByMonth($month, $subject_id){
        return MonthlyPayment::query()
            ->with(['student' => function ($query) {
                $query->select('id', 'name');
            },'teacher' => function ($query) {
                $query->select('id', 'name');
            },'subject' => function ($query) {
                $query->select('id', 'name');
            }])->where('month', $month)->where('subject_id', $subject_id)->get();
    }

    public function getDebtStudents($month, $subject_id){
        return MonthlyPayment::query()
            ->with(['student' => function ($query) {
                $query->select('id','phone');
            }])->where('month', $month)->where('subject_id', $subject_id)
            ->where('status', 0)->get();
    }

    public function filtr($date){
        return MonthlyPayment::query()
            ->with(['student' => function ($query) {
                $query->select('id','name');
            },'subject' => function ($query) {
            $query->select('id', 'name');
            }])->where('date', $date)->get();
    }
}
