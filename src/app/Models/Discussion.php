<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    protected $fillable = [
        'entity_type', 'entity_id', 'title', 'body', 'user_id',
    ];
}
