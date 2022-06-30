<?php

namespace App\Repositories;

use App\Models\Op;
use Illuminate\Support\Facades\DB;

class OpRepository
{

    public function fetchByTeacher(string $teacher_dni)
    {
        return Op::with("course")->where("teacher_dni", $teacher_dni)->get();
    }

    public function store(array $data): void
    {
        try {
            DB::beginTransaction();
            $op = Op::create([
                "teacher_dni" => $data["teacher_dni"],
                "course_code" => $data["course_code"],
                "sts" => $data["sts"],
            ]);
            $op->schedules()->createMany($data["schedules"]);
            DB::commit();
        } catch (\Exception$ex) {
            DB::rollBack();
            throw new \Exception($ex->getMessage());
        }
    }

    public function update(string $dni, array $ops)
    {
        return Op::whereIn("code", $ops)->update([
            "teacher_dni" => $dni,
        ]);
    }

    public function destroy(string $dni)
    {
        DB::table("unit")
            ->whereRaw("exists (select * from op where unit.op_code = op.code and teacher_dni='$dni')")
            ->delete();
        (new ScheduleRepository)->destroyByTeacher($dni);
        return Op::where("teacher_dni", $dni)->delete();
    }
}
