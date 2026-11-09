<?php

namespace Database\Seeders;

use App\Models\College;
use App\Models\Department;
use App\Models\Program;
use Illuminate\Database\Seeder;
use RuntimeException;

class NemsuAcademicCatalogSeeder extends Seeder
{
    /**
     * The catalog was supplied by the project developer for NEMSU Tandag
     * Campus, School Year 2026–2027.
     */
    private const CATALOG = [
        'College of Arts and Sciences' => [
            'abbreviation' => 'CAS',
            'departments' => [
                'Natural Sciences and Mathematics' => [
                    'Bachelor of Science in Biology',
                    'Bachelor of Science in Mathematics',
                    'Bachelor of Science in Environmental Science',
                ],
                'Social Sciences and Humanities' => [
                    'Bachelor of Science in Psychology',
                    'Bachelor of Arts in Political Science',
                    'Bachelor of Arts in Economics',
                ],
                'Language and Literature' => [
                    'Bachelor of Arts in English Language',
                    'Batsilyer ng Sining sa Filipino',
                ],
                'Health Sciences' => [
                    'Bachelor of Science in Midwifery',
                ],
            ],
        ],
        'College of Business and Management' => [
            'abbreviation' => 'CBM',
            'departments' => [
                'Business Administration' => [
                    'Bachelor of Science in Business Administration major in Financial Management',
                    'Bachelor of Science in Business Administration major in Human Resource Management',
                    'Bachelor of Science in Business Administration major in Marketing Management',
                ],
                'Hospitality Management' => [
                    'Bachelor of Science in Hospitality Management',
                ],
                'Public Administration' => [
                    'Bachelor of Public Administration',
                ],
            ],
        ],
        'College of Information Technology Education' => [
            'abbreviation' => 'CITE',
            'departments' => [
                'Computer Science' => [
                    'Bachelor of Science in Computer Science',
                ],
            ],
        ],
        'College of Teacher Education' => [
            'abbreviation' => 'CTE',
            'departments' => [
                'Elementary Education' => [
                    'Bachelor of Elementary Education',
                ],
                'Secondary Education' => [
                    'Bachelor of Secondary Education major in English',
                    'Bachelor of Secondary Education major in Filipino',
                    'Bachelor of Secondary Education major in Science',
                ],
            ],
        ],
        'College of Engineering and Technology' => [
            'abbreviation' => 'CET',
            'departments' => [
                'Civil Engineering' => [
                    'Bachelor of Science in Civil Engineering',
                ],
            ],
        ],
        'College of Accountancy' => [
            'abbreviation' => 'COA',
            'departments' => [
                'Accountancy' => [
                    'Bachelor of Science in Accountancy',
                ],
            ],
        ],
        'College of Law' => [
            'abbreviation' => 'COL',
            'departments' => [
                'Law' => [
                    'Juris Doctor (JD)',
                ],
            ],
        ],
    ];

    public function run(): void
    {
        foreach (self::CATALOG as $collegeName => $collegeData) {
            $college = College::query()->firstOrCreate(
                ['abbreviation' => $collegeData['abbreviation']],
                ['name' => $collegeName]
            );

            if ($college->name !== $collegeName) {
                throw new RuntimeException(
                    "Catalog abbreviation {$collegeData['abbreviation']} is already assigned to {$college->name}."
                );
            }

            foreach ($collegeData['departments'] as $departmentName => $programNames) {
                $department = Department::query()->firstOrCreate([
                    'college_id' => $college->id,
                    'name' => $departmentName,
                ]);

                foreach ($programNames as $programName) {
                    Program::query()->firstOrCreate([
                        'department_id' => $department->id,
                        'name' => $programName,
                    ]);
                }
            }
        }
    }
}
