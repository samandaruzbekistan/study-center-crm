<?php

namespace App\Repositories;

use App\Models\Outlay;
use App\Models\OutlayType;

class OutlayRepository
{
    public function getOutlaysWithTypes(){
        return Outlay::with('types')->latest()->get();
    }

    public function getOutlayTypeByName($name){
        return OutlayType::where('name', $name)->first();
    }

    public function addType($name){
        $outlay = new OutlayType;
        $outlay->name = $name;
        $outlay->save();
    }

    public function addOutlay($type_id, $amount, $date, $cashier_id, $description){
        $o = new Outlay;
        $o->type_id = $type_id;
        $o->amount = $amount;
        $o->date = $date;
        $o->cashier_id = $cashier_id;
        $o->description = $description;
        $o->save();
    }

    public function getOutlayTypes(){
        return OutlayType::all();
    }

    public function get_outlays($type_id){
        return Outlay::with('types')->where('type_id', $type_id)->latest()->get();
    }
}
