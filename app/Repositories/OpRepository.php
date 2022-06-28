<?php

namespace App\Repositories;

use App\Models\Op;
use Illuminate\Support\Facades\DB;

class OpRepository
{
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
}
