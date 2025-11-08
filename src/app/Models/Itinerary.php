<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    protected $fillable = [
        'title', 'slug', 'country_id', 'days', 'content', 'cover_image',
    ];
}
