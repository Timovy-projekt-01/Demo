<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OntologyConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'content',
        'user_file_id'
    ];

    public function userFile()
    {
        return $this->belongsTo(UserFile::class);
    }
}
