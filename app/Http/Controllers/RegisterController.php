<?php

namespace App\Http\Controllers;

use App\Cache\RegisterCache;
use App\Helpers\RegisterHelper;
use App\Http\Requests\RegisterRequest;
use App\Repositories\RegisterRepository;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    protected RegisterRepository $instance;

    public function __construct(RegisterRepository $instance)
    {
        $this->instance = $instance;
    }

    public function fetch(string $dni, string $states, string $mod)
    {
        $register = $this->instance->fetch($dni, str_split($states), $mod);
        return response()->json([
            "value" => $register,
        ]);
    }

    public function fetchByStudent(string $dni)
    {
        return response()->json([
            "values" => $this->instance->fetchByStudent($dni),
        ]);
    }

    public function fetchBySection($section_code, string $inactives)
    {
        return response()->json([
            "values" => $this->instance->fetchBySection($section_code, $inactives === "true"),
        ]);
    }

    public function fetchForAttendance(string $section_code)
    {
        $response = $this->instance->fetchForAttendance($section_code);

        $mapped = $response->map(function ($query) {
            return [
                "entity_identifier" => $query["student_dni"],
                "phone" => $query["student"]["person"]["phone"],
                "state" => "presente",
                "entry_time" => date('H:i'),
                "full_name" => $query["student"]["person"]["name"] . " " . $query["student"]["person"]["lastname"],
            ];
        });
        return response()->json([
            "values" => $mapped,
        ]);
    }

    public function hasOnCache()
    {
        return response()->json(RegisterCache::hasOnCache());
    }

    public function store(RegisterRequest $request)
    {
        $message = "Un estudiante ha sido Matriculado";
        if (!$request->input("invoicing")) {
            if ($request->input("mode") === "Nueva") {
                $this->instance->store(
                    $request->section_code,
                    $request->monthly,
                    $request->student_dni
                );
            } else {
                $this->instance->update($request->all(), $request->code);
                $message = "Una matricula ha sido actualizado";
            }
        } else {
            RegisterCache::setCache($request->all());
            $message = "Una matrícula esta en proceso.";
        }

        if ($request->input("consV")) {
            return RegisterHelper::consv($request["section_code"], $request["student_name"]);
        }

        return response()->json($message);
    }

    public function storeMany(Request $request)
    {
        $this->instance->storeMany($request->all());
        return response()->json([
            "message" => "Correctamente registrado",
        ]);
    }

    public function destroy(string $code)
    {
        $this->instance->destroy($code);
        return response()->json([
            "message" => "Correctamente eliminado",
        ]);
    }
}
