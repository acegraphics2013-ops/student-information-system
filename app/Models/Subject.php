<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $fillable = [
        'subject_code',
        'subject_name',
        'units',
    ];

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }
}
