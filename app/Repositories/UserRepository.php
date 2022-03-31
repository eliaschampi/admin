<?php

namespace App\Repositories;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository
{

    public function fetchByCode(string $code): User
    {
        return User::with(["rol", "branch" => function ($query) {
            $query->select("code", "name");
        }])->find($code);
    }

    public function all()
    {
        return User::with(["rol", "branch" => function ($query) {
            $query->select("code", "name");
        }])->get();
    }

    public function roles()
    {
        return Rol::withCount("users")->get();
    }

    public function fetchByEmail(string $email): ?User
    {
        return User::with(["rol", "branch" => function ($query) {
            $query->select("code", "name");
        }])->where("email", $email)->firstOrFail();
    }

    public function fetchByDni(string $dni): User
    {
        return User::where("dni", $dni)->firstOrFail();
    }

    public function changePassword(User $user, string $password): bool
    {
        $user->password = Hash::make($password);
        return $user->save();
    }

    public function changeCurrentYear(int $year): bool
    {
        $user = User::find($this->user_code);
        $user->current_year = $year;
        return $user->save();
    }

    public function changeImage(User $user, string $fileName): bool
    {
        $user->image = $fileName;
        return $user->save();
    }

    public function store(array $userdata): User
    {
        $userdata["password"] = Hash::make($userdata["dni"]);
        $userdata["image"] = $userdata["gender"] === "F" ? "default_women.png" : "default_men.png";
        return User::create($userdata);
    }

    public function changeBranch($new_branch): bool
    {
        $user = User::find($this->user_code);
        $user->branch_code = $new_branch;
        return $user->save();
    }

    public function update(array $userdata, string $code): bool
    {
        $user = User::find($code);
        $user->name = $userdata["name"];
        $user->lastname = $userdata["lastname"];
        $user->dni = $userdata["dni"];
        $user->gender = $userdata["gender"];
        $user->phone = $userdata["phone"];
        $user->email = $userdata["email"];
        $user->address = $userdata["address"];
        $user->rol_code = $userdata["rol_code"];
        return $user->save();
    }

    public function changeState(string $code, bool $state): bool
    {
        $user = User::find($code);
        $user->state = $state;
        return $user->save();
    }

    public function destroy(User $user): bool
    {
        return $user->delete();
    }
}
