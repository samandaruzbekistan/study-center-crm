<?php

namespace App\Repositories;

use App\Models\NotComeDay;

class NotComeDaysRepository
{
    public function add($arr){
        NotComeDay::insert($arr);
    }
}
