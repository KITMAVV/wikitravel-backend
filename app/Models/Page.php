<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'type','title','slug','summary','content','locale','status','tags','created_by','updated_by','published_at'
    ];

    protected $casts = [
        'tags' => 'array',
        'published_at' => 'datetime',
    ];

    public function revisions()
    {
        return $this->hasMany(PageRevision::class);
    }
}
