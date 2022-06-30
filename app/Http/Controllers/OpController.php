<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\OpRequest;
use App\Repositories\OpRepository;

class OpController extends Controller
{

    private OpRepository $instance;

    public function __construct(OpRepository $instance)
    {
        $this->instance = $instance;
    }

    public function show(string $teacher_dni)
    {
        return response()->json([
            "values" => $this->instance->fetchByTeacher($teacher_dni),
        ]);
    }

    public function store(OpRequest $request)
    {
        $this->instance->store($request->all());
        return response()->json([
            "message" => "Correctamente guardado",
        ]);
    }

    public function update(Request $request, string $teacher_dni)
    {
        $this->instance->update($teacher_dni, $request->input("ops"));
        return response()->json([
            "message" => "Correctamente modificado",
        ]);
    }

    public function destroy(string $dni)
    {
        $this->instance->destroy($dni);
        return response()->json([
            "message" => "Correctamente eliminado",
        ]);
    }
}
