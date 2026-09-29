<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'slug',
        'email',
        'phone',
        'status',
        'company_logo',
        'founder_name',
        'foundation_date',
        'tagline',
        'about_us',
        'whatsapp',
        'website_url',
        'address',
        'maps_embed',
        'social_links',
        'industry',
        'tin_id',
        'trade_license',
    ];
    protected $casts = [
        'social_links'    => 'array',
        'foundation_date' => 'date',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
