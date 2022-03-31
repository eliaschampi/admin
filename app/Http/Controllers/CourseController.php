<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Repositories\CourseRepository;

class CourseController extends Controller
{

    protected CourseRepository $instance;

    public function __construct(CourseRepository $instance)
    {
        $this->instance = $instance;
    }

    public function index()
    {
        return response()->json([
            "courses" => $this->instance->all()
        ]);
    }

    public function store(CourseRequest $request)
    {
        $course = $this->instance->store($request->all());
        return response()->json([
            "res" => "Correctamente guardado",
            "course" => $course,
        ]);
    }

    public function update(CourseRequest $request, $code)
    {
        $this->instance->update($request->all(), $code);
        return response()->json([
            "res" => "Correctamente modificado",
        ]);
    }

    public function destroy($code)
    {
        $this->instance->destroy($code);
        return response()->json("Correctamente eliminado");
    }
}
