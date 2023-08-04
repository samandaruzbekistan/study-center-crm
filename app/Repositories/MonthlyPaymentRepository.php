<?php

namespace App\Repositories;

use App\Models\MonthlyPayment;

class MonthlyPaymentRepository
{
    public function add($data){
        MonthlyPayment::insert($data);
    }
}
