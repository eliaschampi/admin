<?php

namespace App\Repositories;

use App\Models\FamilyWork;

class FamilyworkRepository
{

    public function fetch(string $family_dni): ?FamilyWork
    {
        return FamilyWork::find($family_dni);
    }

    public function upsert(object $familywork): bool
    {
        $data = FamilyWork::find($familywork->family_dni);

        if (empty($data)) {
            $data = new FamilyWork;
            $data->family_dni = $familywork->family_dni;
        }

        $data->name = $familywork->name;
        $data->position = $familywork->position;
        $data->address = $familywork->address;
        $data->phone = $familywork->phone;

        return $data->save();
    }
}
