<?php

namespace App\Repositories;

use App\Models\Admin;

class AdminRepository
{
    public function getAdmin($username){
        return Admin::where('username', $username)->first();
    }
}
