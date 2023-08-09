<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    // Define a one-to-many relationship with monthly_payments
    public function monthlyPayments()
    {
        return $this->hasMany(MonthlyPayment::class);
    }

    // Define a one-to-many relationship with attendance
    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function attachs()
    {
        return $this->hasMany(Attach::class);
    }
}
