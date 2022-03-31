<?php

namespace App\Http\Controllers;

use App\Decorators\CashDecorator;
use App\Helpers\MainHelper;
use App\Http\Requests\UserRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    protected UserRepository $instance;

    public function __construct(UserRepository $instance)
    {
        $this->instance = $instance;
    }

    public function roles()
    {
        return response()->json([
            "roles" => $this->instance->roles(),
        ]);
    }

    public function index()
    {
        return response()->json([
            "users" => $this->instance->all(),
        ]);
    }

    public function show($code)
    {
        return response()->json([
            "user" => $this->instance->fetchByCode($code),
        ]);
    }

    public function store(UserRequest $request)
    {
        $this->instance->store($request->all());
        return response()->json("Correctamente guardado");
    }

    public function update(UserRequest $request, $code)
    {
        $this->instance->update($request->all(), $code);
        CashDecorator::forgetCache();
        return response()->json("Correctamente actualizado");
    }

    public function changeCurrentYear(Request $request)
    {
        $this->instance->changeCurrentYear((int) $request->input("year"));
        return response()->json("Correctamente actualizado");
    }

    public function changeBranch($new_branch)
    {
        $this->instance->changeBranch($new_branch);
        return response()->json("Correctamente actualizado");
    }

    public function changePassword(Request $request, $code)
    {

        $request->validate([
            "current_password" => "required|string",
            "password" => "required|string|min:5|confirmed",
        ]);

        if ($request->input("current_password") === $request->input("password")) {
            return response()->json([
                "message" => "Elija una contraseña diferente",
            ], 500);
        }

        $user = $this->instance->fetchByCode($code);

        if (!Hash::check($request->input("current_password"), $user->password)) {
            return response()->json([
                "message" => "Su contraseña actual no es correcta",
            ], 500);
        }

        $res = $this->instance->changePassword($user, $request->input("password"));

        if ($res) {
            return response()->json("Correctamente modificado");
        }

        return response()->json(false, 500);
    }

    public function destroy($code)
    {
        $user = $this->instance->fetchByCode($code);
        $deleteFile = MainHelper::deleteImage($user->image);
        $deleted = $this->instance->destroy($user);
        if ($deleteFile === false || $deleted === false) {
            return response()->json("No se ha podido eliminar su imagen", 500);
        }
        return response()->json("Correctamente eliminado");
    }

    public function changeImage(Request $request, string $code)
    {
        $request->validate([
            "image" => "required|file|mimes:png,jpg,jpeg|max:512",
        ]);
        $file = $request->file("image");
        $user = $this->instance->fetchByCode($code);
        $stored = MainHelper::changeImage($user->image, $file, $user->dni);
        if ($stored !== false) {
            if ($user->image !== $stored) {
                $this->instance->changeImage($user, $stored);
            }
            return response()->json([
                "message" => "Correctamente actualizado",
                "filename" => $stored,
            ]);
        }
        return response()->json(false, 500);
    }

    public function changeState(Request $request, string $code)
    {
        $this->instance->changeState($code, $request->input("state"));
        return response()->json("Correctamente actualizado");
    }
}
