<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    public function getVisitTimeAttribute($value)
    {
        return date('d-M-Y, g:i a', strtotime($value));
    }
}
