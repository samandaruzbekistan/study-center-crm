<?php

namespace App\Repositories;

use App\Models\MonthlyPayment;

class MonthlyPaymentRepository
{
    public function add($data){
        MonthlyPayment::insert($data);
    }

    public function getPayment($id){
        return MonthlyPayment::find($id);
    }
}
