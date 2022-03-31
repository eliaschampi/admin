<?php

namespace App\Repositories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Builder;
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
        return Teacher::whereHas('person', function (Builder $query) use ($name) {
            $query->whereRaw("concat_ws(' ',lastname, name) ilike '%$name%'");
        })->with(["person", "person.profile"])
            ->limit(5)
            ->get();
    }

    public function fetchBySpe($spe)
    {
        return Teacher::with(["person" => function ($query) {
            return $query->select("dni", "name", "lastname");
        }])->whereIn("specialty", [$spe, "MIX"])->get();
    }

    public function store(array $teacher): Teacher
    {
        return DB::transaction(function () use ($teacher) {
            $personRepository = new PersonRepository();
            $personRepository->store($teacher);
            return Teacher::create($teacher["sub"]);
        });
    }

    public function fetchByCycle(string $c_code)
    {
        return Teacher::with(["person" => function ($query) {
            return $query->select("dni", "name", "lastname");
        }, "person.profile" => function ($query) {
            return $query->select("person_dni", "image");
        }])->whereHas("ops.section.degree", function ($query) use ($c_code) {
            $query->where("cycle_code", $c_code);
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
