<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'student_number',
        'first_name',
        'last_name',
        'email',
        'course',
        'year_level',
    ];

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }
}
