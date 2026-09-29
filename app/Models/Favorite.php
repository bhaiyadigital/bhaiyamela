<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = ['user_id', 'content_id'];
    public function project()
    {
        return $this->belongsTo(Content::class, 'content_id');
    }
}
