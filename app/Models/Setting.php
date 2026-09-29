<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'site_name',
        'site_slogan',
        'logo',
        'favicon',
        'meta_index',
        'privacy_policy_url',
        'terms_url',
        'footer_credit',
        'email',
        'phone',
        'address',
        'map_embed',
    ];

    // ------------------------------------------------------------------
    // Always return the single settings row
    // Creates a blank row if none exists yet
    // ------------------------------------------------------------------
    public static function instance(): static
    {
        return static::firstOrCreate(['id' => 1]);
    }
}