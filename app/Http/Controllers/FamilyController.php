<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonRequest;
use App\Repositories\FamilyRepository;
use Illuminate\Http\Request;

class FamilyController extends Controller
{
    private FamilyRepository $instance;

    public function __construct(FamilyRepository $instance)
    {
        $this->instance = $instance;
    }

    public function show(string $dni)
    {
        return response()->json([
            "value" => $this->instance->fetch($dni),
        ]);
    }

    public function self(string $dni)
    {
        return response()->json([
            "value" => $this->instance->self($dni),
        ]);
    }

    public function fetchBySection(string $section_code)
    {
        return response()->json([
            "values" => $this->instance->fetchBySection($section_code),
        ]);
    }

    public function search(string $name)
    {
        return response()->json([
            "values" => $this->instance->search($name),
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

    public function fetchByStudent(string $student_dni)
    {
        return response()->json([
            "values" => $this->instance->fetchByStudent($student_dni),
        ]);
    }

    public function addStudent(Request $request)
    {
        $this->instance->addStudent(
            $request->input("family_dni"),
            $request->input("student_dni"),
            $request->input("relation_type"),
            $request->input("is_main"),
        );
        return response()->json([
            "message" => "Correctamente agregado",
        ]);
    }

    public function removeStudent(string $family_dni, string $student_dni)
    {
        $this->instance->removeStudent($family_dni, $student_dni);
        return response()->json([
            "message" => "Correctamente actualizado",
        ]);
    }

    public function fetchStudents(string $family_dni)
    {
        return response()->json([
            "values" => $this->instance->fetchStudents($family_dni),
        ]);
    }

    public function printInfo(string $family_dni)
    {

        $family = $this->instance->fetchForPdf($family_dni);
        $rt = config("main.rt");
        $pdf = \PDF::loadView("pdf.family", compact("family", "rt"));
        $pdf->setPaper("A4", "portrait");
        return $pdf->download("family.pdf");
    }
}
