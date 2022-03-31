<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExtraInfoRequest;
use App\Repositories\ExtraInfoRepository;

class ExtraInfoController extends Controller
{
    protected ExtraInfoRepository $instance;

    public function __construct(ExtraInfoRepository $instance)
    {
        $this->instance = $instance;
    }

    public function show(string $student_dni)
    {
        return response()->json([
            "value" => $this->instance->fetch($student_dni),
        ]);
    }

    public function store(ExtraInfoRequest $request)
    {
        $status = $this->instance->upsert((object) $request->all());
        return response()->json([
            "message" => "Correctamente actualizado",
            "issaved" => $status,
        ]);
    }
}
