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

    public function fetchStates()
    {
        $states = config("main.inspection");
        return response()->json([
            "values" => $states,
        ]);
    }

    public function fetchByEntity(string $entity_identifier)
    {
        return response()->json([
            "values" => $this->instance->fetchByEntity($type),
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
            'state' => 'required',
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

    function print(int $code) {

        $inspection = $this->instance->fetchByCode($code);
        $types = [
            "p" => [
                "label" => "permiso",
                "additional" => "Fecha de permiso",
            ],
            "r" => [
                "label" => "requiza",
                "additional" => "Objeto requizado",
            ],
            "l" => [
                "label" => "llamada",
                "additional" => "Nro cel. Activo",
            ],
        ];
        $itype = $types[$inspection->inspection_type];
        $person = config("main.atype.$inspection->entity_type");

        $state = config("main.inspection.$inspection->state");

        $pdf = \PDF::loadView("pdf.inspection", compact("inspection", "itype", "person", "state"));
        return $pdf->download("adj.pdf");
    }
}
