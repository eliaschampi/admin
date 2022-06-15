<?php

namespace App\Repositories;

use App\Models\Inspection;

class InspectionRepository extends BaseRepository
{

    public function fetchByCode(int $code): Inspection | null {
        return Inspection::find($code);
    }

    public function fetchByType(string $type)
    {
        return Inspection::where("inspection_type", $type)
            ->with([
                "user" => function ($query) {
                    $query->select("code", "name");
                },
                "person" => function ($query) {
                    $query->select("dni", "name", "lastname");
                }])
            ->where(function ($query) {
                $query->where("branch_code", $this->branch_code)->orWhereNull("branch_code");
            })
            ->whereYear("created_at", $this->current_year)
            ->orderBy("created_at", "DESC")
            ->get();
    }

    public function fetchByEntity(string $entity_identifier, string $inspection_type)
    {
        return Inspection::with(["user" => function ($query) {
            $query->select("code", "name");
        }])
            ->where("entity_identifier", $entity_identifier)
            ->where("inspection_type", $inspection_type)
            ->whereYear("created_at", date("Y"))
            ->orderBy("created_at", "DESC")
            ->get();
    }

    public function store(array $data): bool
    {
        $inspection = new Inspection();
        $inspection->inspection_type = $data["inspection_type"];
        $inspection->user_code = $this->user_code;
        $inspection->branch_code = $this->branch_code;
        $inspection->state = $data["state"];
        $inspection->entity_type = $data["entity_type"];
        $inspection->entity_identifier = $data["entity_identifier"];
        $inspection->description = $data["description"];
        $inspection->additional = $data["additional"];
        $inspection->extra = $data["extra"];
        return $inspection->save();
    }

    public function update(array $data, int $code): bool
    {
        $inspection = Inspection::find($code);
        $inspection->state = $data["state"];
        $inspection->description = $data["description"];
        $inspection->additional = $data["additional"];
        $inspection->extra = $data["extra"];
        return $inspection->save();
    }

    public function destroy(Inspection $inspection): bool
    {
        return $inspection->delete();
    }

}
