<?php

namespace App\Repositories;

use App\Models\Inspection;

class InspectionRepository extends BaseRepository
{

    public function fetchByType(string $type)
    {
        return Inspection::where("inspection_type", $type)
            ->where("branch_code", $this->branch_code)
            ->whereYear("created_at", $this->current_year)
            ->orderBy("created_at", "DESC")
            ->get();
    }

    public function fetchByEntity(string $entity_identifier)
    {
        return Inspection::where("entity_identifier", $entity_identifier)
            ->whereYear("created_at", $this->current_year)
            ->orderBy("created_at", "DESC")
            ->get();
    }

    public function fetchByCode(int $code): Inspection | null
    {
        return Inspection::find($code);
    }

    public function store(array $data): bool
    {
        $inspection = new Inspection();
        $inspection->inspection_type = $data["inspection_type"];
        $inspection->user_code = $this->user_code;
        $inspection->branch_code = $this->branch_code;
        $inspection->entity_type = $data["entity_type"];
        $inspection->entity_identifier = $data["entity_identifier"];
        $inspection->description = $data["description"];
        $inspection->additional = $data["additional"];
        return $inspection->save();
    }

    public function update(array $data, int $code): bool
    {
        $inspection = Inspection::find($code);
        $inspection->successfully_completed = $data["successfully_completed"];
        $inspection->description = $data["description"];
        $inspection->additional = $data["additional"];
        return $inspection->save();
    }

    public function destroy(int $code): bool
    {
        return Inspection::find($code)->delete();
    }

}
