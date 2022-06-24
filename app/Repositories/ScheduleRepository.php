<?php

namespace App\Repositories;

use App\Models\Schedule;

class ScheduleRepository extends BaseRepository
{
    public function fetchMain(string $section_code)
    {
        return Schedule::with(["op", "op.course"])
            ->whereHas("op", function ($query) use ($section_code) {
                return $query->whereRaw("'$section_code|all' ~* any(sts)");
            })->orderBy("day")->get();
    }

    public function fetchByTeacher(string $teacher_dni)
    {
        return Schedule::with(["op", "op.course"])
            ->whereHas("op", function ($query) use ($teacher_dni) {
                return $query->where("teacher_dni", $teacher_dni)
                             ->whereRaw("'2022|all' ~* any(sts)");
            })->orderBy("day")->get();
    }

    public function store(array $data): Schedule
    {
        return Schedule::create($data);
    }

    public function update(array $data, int $code): bool
    {
        $schedule = Schedule::find($code);
        $schedule->day = $data["day"];
        $schedule->from_time = $data["from_time"];
        $schedule->to_time = $data["to_time"];
        $schedule->state = $data["state"];
        return $schedule->save();
    }

    public function destroy(int $code): bool
    {
        $schedule = Schedule::find($code);
        $op = $schedule->op;
        $deleted = $schedule->delete();
        if ($deleted && $op->schedules->count() === 0) {
            return $op->delete();
        }
        return $deleted;
    }
}
