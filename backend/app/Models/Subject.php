<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    /** @use HasFactory<\Database\Factories\SubjectFactory> */
    use HasFactory;
    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
