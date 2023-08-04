<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Change the number to the desired number of students you want to create
        $numberOfStudents = 250;

        // Generate temporary data for the students table
        Student::factory()->count($numberOfStudents)->create();
    }
}
