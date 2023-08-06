<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    public function attachs()
    {
        return $this->hasMany(Attach::class);
    }

    public function monthlyPayments()
    {
        return $this->hasMany(MonthlyPayment::class, 'student_id');
    }
}
