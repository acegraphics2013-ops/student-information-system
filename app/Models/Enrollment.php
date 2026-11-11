<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    public const DEFAULT_SEMESTER = '1st Semester';

    public const SEMESTERS = [
        '1st Semester',
        '2nd Semester',
        'Summer Semester',
    ];

    protected $fillable = [
        'student_id',
        'subject_id',
        'status',
        'semester',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
