<?php

namespace App\Repositories;

use App\Models\Person;

class PersonRepository extends BaseRepository
{
    public function fetch(string $type, string $dni): Person
    {
        $person = Person::with("profile");
        if ($type === "student") {
            $person->whereHas("student", function ($query) {
                $query->where("branch_code", $this->branch_code);
            });
        }
        return $person->findOrFail($dni);
    }

    public function fetchForCard(string $s_code)
    {
        return Person::select()->whereRaw(
            "exists (select * from register where student_dni = person.dni and section_code = '$s_code' and state = 'a')")
            ->get();
    }

    public function fetchForCardT()
    {
        return Person::select()->whereRaw(
            "exists (select * from teacher where dni = person.dni and state = true)"
        )->get();
    }

    public function fetchSingle(string $dni): Person
    {
        return Person::select("dni", "name", "lastname")
            ->with(["profile" => function ($query) {
                $query->select("person_dni", "image");
            }])->find($dni);
    }

    public function store(array $person): Person
    {
        return Person::create($person);
    }

    public function update(array $persondata, string $dni): bool
    {
        $person = Person::find($dni);
        $person->name = $persondata["name"];
        $person->lastname = $persondata["lastname"];
        $person->birthdate = $persondata["birthdate"];
        $person->ubigeo = $persondata["ubigeo"];
        $person->district = $persondata["district"];
        $person->address = $persondata["address"];
        $person->email = $persondata["email"];
        $person->gender = $persondata["gender"];
        $person->phone = $persondata["phone"];
        $person->obs = $persondata["obs"];
        return $person->save();
    }

    public function updatePhone(string $dni, string $phone)
    {
        $person = Person::find($dni);
        $person->phone = $phone;
        $person->save();
    }

    public function destroy(Person $person): bool
    {
        return $person->delete();
    }
}
