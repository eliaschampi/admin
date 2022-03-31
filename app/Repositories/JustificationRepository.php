<?php

namespace App\Repositories;

use App\Models\Attendance;

class JustificationRepository
{


    private function fetchAttendance(int $code): ?Attendance
    {
        return (new AttendanceRepository)->fetchByCode($code);
    }

    public function store(int $attendance_code, string $fileName, string $description)
    {
        $attendance = $this->fetchAttendance($attendance_code);
        $attendance->justification()->create([
            "attached" => $fileName,
            "description" => $description,
        ]);
        $attendance->state = "justificado";
        return $attendance->save();
    }

    public function toggle(int $attendance_code, string $aprove, $deletefile): bool
    {
        $attendance = $this->fetchAttendance($attendance_code);
        if ($aprove === "yes") {
            $attendance->state = "justificado";
        } else {
            if ($deletefile($attendance->justification->attached)) {
                $attendance->justification()->delete();
                $attendance->state = "falta";
            }
        }
        return $attendance->save();
    }
}
