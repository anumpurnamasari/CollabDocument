<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentRevision extends Model
{
    protected $fillable = [
        'document_id',
        'user_id',
        'title',
        'content',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}