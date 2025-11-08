<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $fillable = [
        'name', 'slug', 'country_id', 'address', 'rating', 'description', 'cover_image',
    ];
}
