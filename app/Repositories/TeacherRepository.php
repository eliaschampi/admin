<?php

namespace App\Repositories;

use App\Models\Teacher;
use Illuminate\Support\Facades\DB;

class TeacherRepository extends BaseRepository
{

    public function fetchAllWithPagination()
    {
        return Teacher::with("person")->paginate($this->paginateNumber());
    }

    public function self(string $dni)
    {
        return Teacher::find($dni);
    }

    public function teacherIsActive($dni)
    {
        return Teacher::where("dni", $dni)->where("state", true)->exists();
    }

    public function fetch(string $dni): Teacher
    {
        return Teacher::with("person")->findOrFail($dni);
    }

    public function search(string $name)
    {
        return Teacher::with(["person", "person.profile"])
            ->join("person", "teacher.dni", "=", "person.dni")
            ->orderByRaw("(person.name || ' ' || person.lastname) <-> '$name'")
            ->limit(6)
            ->get();
    }

    public function store(array $teacher): Teacher
    {
        return DB::transaction(function () use ($teacher) {
            $personRepository = new PersonRepository();
            $personRepository->store($teacher);
            return Teacher::create($teacher["sub"]);
        });
    }

    public function fetchBySection(string $section_code)
    {
        return Teacher::with(["person" => function ($query) {
            return $query->select("dni", "name", "lastname");
        }, "person.profile" => function ($query) {
            return $query->select("person_dni", "image");
        }])->whereHas("ops", function ($query) use ($section_code) {
            $query->whereRaw("'$section_code|all' ~* any(sts)");
        })->get();
    }

    public function update(array $teacher, string $dni): bool
    {
        return DB::transaction(function () use ($teacher, $dni) {
            $personRepository = new PersonRepository();
            $personRepository->update($teacher, $dni);
            $teach = Teacher::find($dni);
            $teach->startdate = $teacher["sub"]["startdate"];
            $teach->specialty = $teacher["sub"]["specialty"];
            return $teach->save();
        });
    }

    public function changeState(string $dni): bool
    {
        $teach = Teacher::find($dni);
        $teach->state = !$teach->state;
        return $teach->save();
    }

    public function fetchCountByBranch(): int
    {
        return Teacher::where("state", true)->count();
    }
}
