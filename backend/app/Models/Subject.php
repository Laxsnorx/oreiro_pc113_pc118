<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    /** @use HasFactory<\Database\Factories\SubjectFactory> */
    use HasFactory;
    protected $fillable = ['code', 'description', 'year', 'units', 'instructor_id'];
    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
