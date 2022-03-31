<?php

namespace App\Http\Controllers;

use App\Mail\PasswordGeneratedMailable;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    //use AuthenticatesUsers;

    /**
     * @var int $maxLoginAttempts
     * this is a constant
     */
    protected $maxLoginAttempts = 5;

    /**
     * @var int $lockoutTime
     * this is a constant
     */
    protected $lockoutTime = 300;

    /**
     * @var UserRepository
     */
    protected $instance;

    public function __construct(UserRepository $instance)
    {
        $this->instance = $instance;
    }

    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email|max:100",
            "password" => "required|max:100",
        ]);

        $credentials = $request->only("email", "password");
        $user = $this->instance->fetchByEmail($request->input("email"));

        if (($user->state === false) || (!$user->rol_code == 'A')) {

            return response()->json([
                "success" => false,
                "error" => "Acceso no autorizado",
            ], 401);
        }

        try {
            if (!$token = JWTAuth::attempt($credentials)) {

                return response()->json([
                    "success" => false,
                    "error" => "Contraseña Incorrecta",
                ], 422);
            }
        } catch (JWTException $e) {
            return response()->json([
                "success" => false,
                "error" => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            "success" => true,
            "access_token" => $token,
            "token_type" => "bearer",
            "user" => $user,
            "expires_in" => auth('api')->factory()->getTTL() * 60,
        ]);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(["success" => true]);
    }

    public function recover(Request $request)
    {
        $request->validate([
            "email" => "required|email|max:100",
            "dni" => "required|min:8|max:8",
        ]);

        $user = $this->instance->fetchByDni($request->dni);

        if ($request->input("email") === $user->email) {
            $newPass = Str::random(10);
            if ($this->instance->changePassword($user, $newPass)) {
                Mail::to($user->email)->send(new PasswordGeneratedMailable($newPass));
                return response()->json("Te hemos enviado un mensaje a tu Correo");
            }
        }
        return response()->json(false, 500);
    }

    public function check(Request $request)
    {
        return response()->json([
            "valid" => auth()->validate($request->all()),
        ]);
    }

    public function refresh(Request $request)
    {
        $valid = false;
        $user = null;
        if (auth()->validate($request->all())) {
            $user = $this->instance->fetchByEmail($request->input("email"));
            $user["access_token"] = auth()->refresh();
            $valid = true;
        }
        return response()->json([
            "user" => $user,
            "valid" => $valid,
            "hasuser" => true,
        ]);
    }
}
