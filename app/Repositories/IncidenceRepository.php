<?php

namespace App\Repositories;

use App\Models\Incidence;
use Illuminate\Support\Facades\DB;

class IncidenceRepository extends BaseRepository
{

    public function fetchByCode(int $code): Incidence
    {
        return Incidence::find($code);
    }

    public function fetchByMonth(string $month)
    {
        return Incidence::with(["user" => function ($query) {
            $query->select("code", "name");
        }, "students", "students.person" => function ($query) {
            $query->select("dni", "name", "lastname");
        }])
            ->whereHas("user", function ($query) {
                $query->where("branch_code", $this->branch_code);
            })
            ->whereYear("created_at", $this->current_year)
            ->whereMonth("created_at", $month)
            ->latest()
            ->paginate($this->paginateNumber());
    }

    public function store(array $data, string $filename): void
    {
        DB::transaction(function () use ($data, $filename) {
            if (empty($data["created_at"])) {
                $data["created_at"] = now();
            }
            $data["user_code"] = $this->user_code;
            $data["image_attached"] = $filename;
            $incidence = Incidence::create($data);
            $students = array_map(function ($item) {
                return $item["dni"];
            }, $data["students"]);
            $incidence->students()->attach($students);
        });
    }

    public function update($data, $code)
    {
        $incidence = Incidence::find($code);
        if (empty($data["created_at"])) {
            $data["created_at"] = now();
        }
        $incidence->type = $data["type"];
        $incidence->title = $data["title"];
        $incidence->description = $data["description"];
        $incidence->agreement = $data["agreement"];
        $incidence->created_at = $data["created_at"];
        return $incidence->save();
    }

    public function destroy(Incidence $incidence): bool | null
    {
        $incidence->students()->detach();
        return $incidence->delete();
    }
}
