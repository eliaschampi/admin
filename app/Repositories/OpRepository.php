<?php

namespace App\Repositories;

use App\Models\Op;
use Illuminate\Support\Facades\DB;

class OpRepository
{

    public function fetchBySection($s_code)
    {
        return Op::with(["course", "teacher", "teacher.person" => function ($query) {
            return $query->select("dni", "name", "lastname");
        }])->where("section_code", $s_code)->get();
    }

    public function store(array $data): void
    {
        try {
            DB::beginTransaction();
            $op = Op::updateOrCreate([
                "section_code" => $data["section_code"],
                "teacher_dni" => $data["teacher_dni"],
                "course_code" => $data["course_code"],
            ], $data);
            $op->schedules()->createMany($data["schedules"]);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            throw new \Exception($ex->getMessage());
        }
    }
}
