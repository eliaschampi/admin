<?php

namespace App\Repositories;

use App\Models\Register;
use App\Models\RegisterIncome;

class RegisterRepository extends BaseRepository
{

    public function fetchByCode(string $code) {
        return Register::find($code);
    }

    public function fetch(string $dni, array $states, $mod = "current_register")
    {
        $modelRegister = Register::where("student_dni", $dni)->whereIn("state", $states);
        $register = "";
        if ($mod === "last_register") {
            $register = $modelRegister->latest()->first();
        } else {
            $year = $mod;
            if ($mod === "current_register") {
                $year = $this->current_year;
            }
            $register = $modelRegister->whereYear("created_at", $year)->first();
        }
        return $register;
    }

    public function fetchSingle(string $dni)
    {
        return Register::where("student_dni", $dni)
            ->where("state", "a")
            ->whereYear("created_at", $this->current_year)
            ->first();
    }

    public function fetchByStudent(string $dni)
    {
        return Register::where("student_dni", $dni)->latest()->get();
    }

    public function fetchBySection(string $s_code, bool $inactives)
    {
        $states = $inactives ? ["i", "p"] : ["a", "f"];
        return Register::with(["student", "student.person" => function ($query) {
            $query->select("dni", "name", "lastname", "phone");
        }, "student.person.profile" => function ($query) {
            $query->select("person_dni", "image", "last_logout");
        }])
            ->where("section_code", $s_code)
            ->whereIn("state", $states)->get();
    }

    public function fetchForAttendance(string $section_code, int $priority)
    {
        return Register::where("state", "a")->where("section_code", $section_code)
            ->whereDoesntHave("student.person.attendances", function ($query) use ($priority) {
                $query->whereDate("created_at", now())->where("priority", $priority);
            })->get();
    }

    public function store(string $section_code, string $monthly, string $dni, string $state = "a")
    {
        $year = "M" . date('Y');
        $last = Register::selectRaw("lpad((max(substring(code, 7,4))::int + 1)::text,4, '0') as m")
            ->where('code', 'like', "$year-%")
            ->first();
        $reg = Register::create([
            "code" => $year . "-" . (empty($last->m) ? "0001" : $last->m),
            "section_code" => $section_code,
            "monthly" => $monthly,
            "state" => $state,
            "student_dni" => $dni,
        ]);
        return $reg->code;
    }

    public function update(array $data, string $code)
    {
        $register = Register::find($code);
        $register->monthly = $data["monthly"];
        $register->section_code = $data["section_code"];
        $register->state = $data["state"];
        return $register->save() ? $code : false;
    }

    public function fetchCountByBranch(): int
    {
        return Register::where("section_code", "like", $this->herelike())->count();
    }

    public function storeOrUpdate(array | bool $register)
    {
        if ($register !== false) {
            if ($register["mode"] === "Nueva") {
                return $this->store(
                    $register["section_code"],
                    $register["monthly"],
                    $register["student_dni"]
                );
            }
            return $this->update($register, $register["code"]);
        }
        return false;
    }

    public function fetchForMigrate(string $last_degree)
    {
        return Register::with(["student", "student.person" => function ($query) {
            $query->select("dni", "name", "lastname", "phone");
        }])
            ->where("state", "f")
            ->where("section_code", "like", $last_degree . "_")
            ->whereDoesntHave("student.registers", function ($query) use ($last_degree) {
                $query->where("section_code", "like", $this->current_year . "%");
            })
            ->get();
    }

    public function storeMany(array $data)
    {
        $d_code = $data["degree_code"];
        try {
            \DB::beginTransaction();
            (new SectionRepository)->storeMany($data["sections"], $d_code);
            foreach ($data["registers"] as $register) {
                $this->store(
                    $d_code . substr($register["section_code"], -1),
                    $data["monthly"],
                    $register["student_dni"],
                    "p"
                );
            }
            \DB::commit();
        } catch (\Exception $ex) {
            \DB::rollBack();
            throw new \Exception($ex->getMessage());
        }
    }

    public function destroy(string $code)
    {
        $hasIncomes = RegisterIncome::where("register_code", $code)->exists();
        if ($hasIncomes) {
            throw new \Exception("Esta matr??cula tiene dependencias");
        }
        return Register::find($code)->delete();
    }
}
