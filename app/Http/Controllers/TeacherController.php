<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonRequest;
use App\Repositories\ConfigRepository;
use App\Repositories\TeacherRepository;

class TeacherController extends Controller
{

    private TeacherRepository $instance;

    public function __construct(TeacherRepository $instance)
    {
        $this->instance = $instance;
    }

    public function fetchByState(string $state)
    {
        return response()->json([
            "values" => $this->instance->fetchByState($state === "true"),
        ]);
    }

    public function self(string $dni)
    {
        return response()->json([
            "value" => $this->instance->self($dni),
        ]);
    }

    public function show(string $dni)
    {
        return response()->json([
            "value" => $this->instance->fetch($dni),
        ]);
    }

    public function search(string $name)
    {
        return response()->json([
            "values" => $this->instance->search($name),
        ]);
    }

    public function fetchBySection(string $c_code)
    {
        return response()->json([
            "values" => $this->instance->fetchBySection($c_code),
        ]);
    }

    public function store(PersonRequest $request)
    {
        $this->instance->store($request->all());
        return response()->json([
            "message" => "Correctamente guardado",
        ]);
    }

    public function update(PersonRequest $request, string $dni)
    {
        $this->instance->update($request->all(), $dni);
        return response()->json([
            "message" => "Correctamente actualizado",
        ]);
    }

    public function changeState(string $dni)
    {
        $this->instance->changeState($dni);
        return response()->json([
            "message" => "Correctamente actualizado",
        ]);
    }
}
