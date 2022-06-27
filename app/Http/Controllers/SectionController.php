<?php

namespace App\Http\Controllers;

use App\Helpers\MainHelper;
use App\Http\Requests\SectionRequest;
use App\Repositories\SectionRepository;
use Illuminate\Http\Request;

class SectionController extends Controller
{

    protected SectionRepository $instance;

    public function __construct(SectionRepository $instance)
    {
        $this->instance = $instance;
    }

    public function index()
    {
        return response()->json([
            "sumaries" => $this->instance->fetchSumary(),
        ]);
    }

    public function create()
    {
        return response()->json([
            "values" => $this->instance->fetchByYearAndBranch(),
        ]);
    }

    public function store(SectionRequest $request)
    {
        $this->instance->store($request->all());
        return response()->json("Correctamente guardado");
    }

    public function update(Request $request, string $section_code)
    {
        $this->instance->changeTutor($section_code, $request->input("teacher_dni"));
        return response()->json("Correctamente modificado");
    }

    public function destroy(string $code)
    {
        $this->instance->destroy($code);
        return response()->json("Correctamente eliminado");
    }

    public function fetchByDegree(string $degree_code)
    {
        return response()->json([
            "values" => $this->instance->fetchByDegree($degree_code),
        ]);
    }

    public function fetchForMigrate(string $degree_code)
    {
        $lastcode = MainHelper::lastCode($degree_code);
        $sections = $this->instance->fetchByDegree($lastcode);
        $registers = (new \App\Repositories\RegisterRepository)->fetchForMigrate($lastcode);
        return response()->json([
            "sections" => $sections,
            "registers" => $registers,
            "last_code" => $lastcode,
        ]);
    }
}
