<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'purpose',
        'property_type',
        'size',
        'city',
        'location',
        'name',
        'email',
        'phone',
        'status',
    ];

  
}