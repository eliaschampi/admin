<?php

namespace App\Repositories;

use App\Models\Ailment;
use Illuminate\Database\Eloquent\Collection;

class AilmentRepository
{
    public function fetchByCode(int $code): ?Ailment
    {
        return Ailment::find($code);
    }

    public function fetchByStudent(string $student_dni): Collection
    {
        return Ailment::whereStudentDni($student_dni)->get();
    }

    public function store(array $res): Ailment
    {
        return Ailment::create($res);
    }

    public function update(array $res, int $code): bool
    {
        $ailment = Ailment::find($code);
        $ailment->type = $res["type"];
        $ailment->ailment = $res["ailment"];
        $ailment->cause = $res["cause"];
        $ailment->treatment = $res["treatment"];
        return $ailment->save();
    }

    public function upload(Ailment $instance, string $attached)
    {
        $instance->attached = $attached;
        return $instance->save();
    }

    public function destroy(Ailment $ailment): ?bool
    {
        return $ailment->delete();
    }
}
