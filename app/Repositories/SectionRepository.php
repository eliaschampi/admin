<?php

namespace App\Repositories;

use App\Models\Section;

class SectionRepository extends BaseRepository
{

    public function fetchSumary()
    {
        return Section::with("degree")
            ->with(["tutor", "tutor.person" => function ($query) {
                $query->select("dni", "name", "lastname");
            }])
            ->withCount('registers')
            ->orderByDesc("code", "ASC")
            ->where("code", "like", $this->herelike())->get();
    }

    public function fetchByDegree(string $d_code)
    {
        return Section::where("degree_code", $d_code)
            ->withCount("registers")
            ->orderByDesc("code", "ASC")
            ->get();
    }

    //pluked
    public function fetchNameByDegree(string $d_code)
    {
        return Section::where("degree_code", $d_code)->pluck("name");
    }

    public function store(array $section): Section
    {
        return Section::create($section);
    }

    public function changeTutor(string $section_code, string $dni)
    {
        $section = Section::find($section_code);
        $section->tutor = $dni;
        return $section->save();
    }

    public function destroy(string $code): bool
    {
        $section = Section::find($code);
        if (isset($section->student_dni)) {
            $section->student_dni = null;
            $section->save();
        }
        return $section->delete();
    }

    public function storeMany(array $sections, string $d_code): int
    {
        $count = 0;
        foreach ($sections as $value) {
            Section::create([
                "code" => $d_code . $value["name"],
                "degree_code" => $d_code,
                "name" => $value["name"],
            ]);
            $count++;
        }
        return $count;
    }
}
