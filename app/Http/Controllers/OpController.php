<?php

namespace App\Http\Controllers;

use App\Http\Requests\OpRequest;
use App\Repositories\OpRepository;

class OpController extends Controller
{

    protected OpRepository $instance;

    public function __construct(OpRepository $instance)
    {
        $this->instance = $instance;
    }

    public function store(OpRequest $request)
    {
        $this->instance->store($request->all());
        return response()->json([
            "message" => "Corectamente guardado"
        ]);
    }
}
