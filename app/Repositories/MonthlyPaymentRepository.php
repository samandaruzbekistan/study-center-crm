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

    public function getPaymentByMonth($student_id, $month, $subject_id){
        return MonthlyPayment::where('student_id', $student_id)
            ->where('subject_id', $subject_id)
            ->where('month', $month)
            ->first();
    }

    public function deleteNowAndNextPayments($monthToDelete,$attach_id){
        MonthlyPayment::where('month', '>=', $monthToDelete)
            ->where('attach_id',$attach_id)
            ->delete();
    }

    public function deleteNextPayments($monthToDelete,$attach_id){
        MonthlyPayment::where('month', '>', $monthToDelete)
            ->where('attach_id',$attach_id)
            ->delete();
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

    public function getTeacherPayments100($teacher_id){
        return MonthlyPayment::query()
            ->with(['student' => function ($query) {
                $query->select('id', 'name');
            }, 'teacher' => function ($query) {
                $query->select('id', 'name');
            }, 'subject' => function ($query) {
                $query->select('id', 'name');
            }])->where('teacher_id',$teacher_id)->where('date','!=', null)->orderBy('date', 'desc')->paginate(100);
    }

    public function getTeacherPaymentsByMonth($month,$teacher_id){
        return MonthlyPayment::query()
            ->with(['student' => function ($query) {
                $query->select('id', 'name');
            }, 'teacher' => function ($query) {
                $query->select('id', 'name');
            }, 'subject' => function ($query) {
                $query->select('id', 'name');
            }])->where('teacher_id',$teacher_id)->whereMonth('date',$month)->where('date','!=', null)->orderBy('date', 'desc')->get();
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

    public function addPayment($attach_id,$student_id, $subject_id, $teacher_id, $amount,$month, $amount_paid){
        $payment = new MonthlyPayment;
        $payment->attach_id = $attach_id;
        $payment->student_id = $student_id;
        $payment->subject_id = $subject_id;
        $payment->teacher_id = $teacher_id;
        $payment->amount = $amount;
        $payment->month = $month;
        $payment->status = 1;
        $payment->date = date('Y-m-d');
        $payment->amount_paid = $amount_paid;
        $payment->save();
    }

    public function updatePayment($id, $amount){
        $payment = MonthlyPayment::find($id);
        $payment->amount = $amount;
        $payment->paid_cashier_id = session('id');
        $payment->save();
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
            ->select(
                DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),
                DB::raw('SUM(amount_paid) as total')
            )
            ->groupBy(DB::raw('DATE_FORMAT(date, "%Y-%m")'))
            ->orderBy(DB::raw('DATE_FORMAT(date, "%Y-%m")'), 'desc')
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
