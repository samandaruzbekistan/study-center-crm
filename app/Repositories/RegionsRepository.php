<?php

namespace App\Repositories;

use App\Models\District;
use App\Models\Quarter;
use App\Models\Region;

class RegionsRepository
{
    public function getRegions(){
        return Region::orderBy('name', 'asc')->get();
    }

    public function getDistrictsByRegionId($region_id){
        return District::where('region_id', $region_id)->orderBy('name', 'asc')->get();
    }

    public function getQuartersByDistrictId($district_id){
        return Quarter::where('region_id', $district_id)->orderBy('name', 'asc')->get();
    }
}
