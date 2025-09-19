<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageRevision extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id','editor_id','summary','is_minor','snapshot','diff'
    ];

    protected $casts = [
        'is_minor' => 'boolean',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
