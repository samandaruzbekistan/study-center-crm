<?php

namespace App\Repositories;

use App\Models\AdminOutlay;

class AdminOutlayRepository
{
    public function getOutlays(){
        return AdminOutlay::latest()->get();
    }

    public function add($amount, $description, $date){
        $o = new AdminOutlay;
        $o->amount = $amount;
        $o->description = $description;
        $o->date = $date;
        $o->save();
    }
}
