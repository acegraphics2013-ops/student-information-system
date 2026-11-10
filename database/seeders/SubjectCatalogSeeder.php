<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectCatalogSeeder extends Seeder
{
    /**
     * User-supplied demonstration catalog; this does not assert official curricula.
     * Existing subject rows are intentionally never overwritten.
     */
    private const SUBJECTS = [
        ['GE101', 'Understanding the Self', 3],
        ['GE102', 'Readings in Philippine History', 3],
        ['GE103', 'The Contemporary World', 3],
        ['GE104', 'Mathematics in the Modern World', 3],
        ['GE105', 'Purposive Communication', 3],
        ['GE106', 'Art Appreciation', 3],
        ['GE107', 'Science, Technology, and Society', 3],
        ['GE108', 'Ethics', 3],
        ['FIL101', 'Komunikasyon sa Akademikong Filipino', 3],
        ['ENG101', 'English for Academic Purposes', 3],
        ['IT101', 'Introduction to Computing', 3],
        ['IT102', 'Computer Programming 1', 3],
        ['IT103', 'Computer Programming 2', 3],
        ['IT201', 'Data Structures and Algorithms', 3],
        ['IT202', 'Database Management Systems', 3],
        ['CS101', 'Discrete Structures', 3],
        ['MATH101', 'College Algebra', 3],
        ['MATH102', 'Calculus 1', 3],
        ['PE101', 'Physical Fitness and Wellness', 2],
        ['NSTP101', 'National Service Training Program 1', 3],
    ];

    public function run(): void
    {
        foreach (self::SUBJECTS as [$code, $name, $units]) {
            Subject::query()->firstOrCreate(
                ['subject_code' => $code],
                ['subject_name' => $name, 'units' => $units]
            );
        }
    }
}
