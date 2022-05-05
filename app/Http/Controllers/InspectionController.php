<?php

namespace App\Http\Controllers;

use App\Http\Requests\InspectionRequest;
use App\Repositories\InspectionRepository;
use Illuminate\Http\Request;

class InspectionController extends Controller
{

    protected InspectionRepository $instance;

    public function __construct(InspectionRepository $instance)
    {
        $this->instance = $instance;
    }

    public function fetchByType(string $type)
    {
        return response()->json([
            "values" => $this->instance->fetchByType($type),
        ]);
    }

    public function fetchByEntity(string $entity_identifier)
    {
        return response()->json([
            "values" => $this->instance->fetchByEntity($type),
        ]);
    }

    public function fetchByCode(int $code)
    {
        return response()->json([
            "value" => $this->instance->fetchByCode($code),
        ]);
    }

    public function store(InspectionRequest $request)
    {
        $this->instance->store($request->all());
        return response()->json([
            "message" => "Correctamente guardado",
        ]);
    }

    public function update(Request $request, int $code)
    {
        $validated = $request->validate([
            'successfully_completed' => 'required',
            'description' => 'required|max:400',
            'additional' => 'required|max:150',
        ]);
        $this->instance->update($validated, $code);
        return response()->json([
            "message" => "Correctamente actualizado",
        ]);
    }

    public function destroy(int $code)
    {
        $this->instance->destroy($code);
        return response()->json([
            "message" => "Correctamente Eliminado",
        ]);
    }
}
