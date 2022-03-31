<?php

namespace App\Repositories;

use App\Models\Course;

class CourseRepository
{

    public function all()
    {
        return Course::all();
    }

    public function store(array $coursedata): Course
    {
        return Course::create($coursedata);
    }

    public function update(array $coursedata, int $code): bool
    {
        $course = Course::find($code);
        $course->name = $coursedata["name"];
        $course->type = $coursedata["type"];
        return $course->save();
    }

    public function destroy(int $code): bool
    {
        return Course::find($code)->delete();
    }
}
