<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outlay extends Model
{
    use HasFactory;

    public function types()
    {
        return $this->belongsTo(OutlayType::class, 'type_id');
    }
}
