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

    public const YEAR_LEVELS = [1, 2, 3, 4, 5];

    protected $fillable = [
        'student_id',
        'subject_id',
        'status',
        'semester',
        'year_level',
    ];

    /**
     * Human readable label for a year level, e.g. 1 => "1st Year".
     */
    public static function yearLevelLabel(?int $yearLevel): string
    {
        $suffixes = [1 => 'st', 2 => 'nd', 3 => 'rd', 4 => 'th', 5 => 'th'];

        if ($yearLevel === null || ! isset($suffixes[$yearLevel])) {
            return 'Year not set';
        }

        return $yearLevel.$suffixes[$yearLevel].' Year';
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
