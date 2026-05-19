<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function revisions()
    {
        return $this->hasMany(DocumentRevision::class);
    }

    public function collaborators()
    {
        return $this->hasMany(DocumentCollaborator::class);
    }

    public function sharedUsers()
    {
        return $this->belongsToMany(User::class, 'document_collaborators')
            ->withPivot('permission')
            ->withTimestamps();
    }
}