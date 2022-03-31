<?php

namespace App\Repositories;

use App\Models\ExtraInfo;

class ExtraInfoRepository
{

    public function fetch(string $student_dni): ?ExtraInfo
    {
        return ExtraInfo::find($student_dni);
    }

    public function upsert(object $extrainfo): bool
    {
        $data = ExtraInfo::find($extrainfo->student_dni);

        if (empty($data)) {
            $data  = new ExtraInfo;
            $data->student_dni = $extrainfo->student_dni;
        }
        $data->religion = $extrainfo->religion;
        $data->livemode = $extrainfo->livemode;
        $data->weight = $extrainfo->weight;
        $data->size = $extrainfo->size;
        $data->live_together = $extrainfo->live_together;
        $data->additional = $extrainfo->additional;
        $data->reg_reason = $extrainfo->reg_reason;
        return $data->save();
    }
}
