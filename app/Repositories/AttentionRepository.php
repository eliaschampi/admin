<?php

namespace App\Repositories;

use App\Models\Attention;

class AttentionRepository extends BaseRepository
{

    public function fetchByCode(int $code): Attention
    {
        return Attention::find($code);
    }

    public function fetchByMonth(string $month)
    {
        return Attention::with(["user" => function ($query) {
            $query->select("code", "name");
        }, "person" => function ($query) {
            $query->select("dni", "name", "lastname");
        }])->where("branch_code", $this->branch_code)
            ->whereYear("created_at", $this->current_year)
            ->whereMonth("created_at", $month)
            ->latest()
            ->paginate($this->paginateNumber());
    }

    public function store(array $data, string $filename): Attention
    {
         if (empty($data["created_at"])) {
            $data["created_at"] = now();
        }
        $data["user_code"] = $this->user_code;
        $data["branch_code"] = $this->branch_code;
        $data["file_attached"] = $filename;
        return Attention::create($data);
    }

    public function update(array $data, int $code): bool
    {
         if (empty($data["created_at"])) {
            $data["created_at"] = now();
        }
        $attention = $this->fetchByCode($code);
        $attention->introduction = $data["introduction"];
        $attention->entity_identifier = $data["entity_identifier"];
        $attention->type = $data["type"];
        $attention->title = $data["title"];
        $attention->description = $data["description"];
        $attention->conclusion = $data["conclusion"];
        $attention->is_visible = $data["is_visible"];
        return $attention->save();
    }

    public function destroy(Attention $attention): bool | null
    {
        return $attention->delete();
    }
}
