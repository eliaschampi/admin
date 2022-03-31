<?php

namespace App\Repositories;

use App\Models\Degree;
use App\Models\Register;
use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class StudentRepository extends BaseRepository
{

    public function fetchForPdf(string $dni): Student
    {
        return Student::with("person", "registers", "families")->find($dni);
    }

    public function fetch(string $dni): Student
    {
        return Student::with("person")->findOrFail($dni);
    }

    public function search(int $branch_code, bool $only_current_reg, string $name)
    {
        $student = Student::where("branch_code", $branch_code);

        if ($only_current_reg) {
            $student->whereHas("registers", function ($query) {
                return $query->where("section_code", "like", $this->current_year . "%");
            });
        }

        return $student
            ->whereHas("person", function (Builder $query) use ($name) {
                $query->whereRaw("concat_ws(' ',lastname, name) ilike '%$name%'");
            })->with(["person", "person.profile"])
            ->limit(5)
            ->get();
    }

    public function updateBranch(string $branch, string $dni)
    {
        return DB::transaction(function () use ($branch, $dni) {
            $student = Student::find($dni);
            $register = Register::where("section_code", "like", date('Y') . "______")
                ->where("student_dni", $dni)
                ->first();
            if ($register !== null) {
                $pref = substr($register->section_code, 0, 7);
                $section = substr($register->section_code, -1);
                $degree = $pref . $branch . substr($register->section_code, -2, 1);
                if (Degree::find($degree) === null) {
                    throw new \Exception("No hay esta sección en la sede destino");
                }
                $register->section_code = $degree . $section;
                $register->save();
            }
            $student->branch_code = $branch;
            return $student->save();
        });
    }

    public function store(array $student): Student
    {
        return DB::transaction(function () use ($student) {
            $personRepository = new PersonRepository();
            $personRepository->store($student);
            return Student::create([
                "dni" => $student["dni"],
                "branch_code" => $this->branch_code,
            ]);
        });
    }

    public function fetchCountByBranch()
    {
        return Student::where("branch_code", $this->branch_code)->count();
    }
}
