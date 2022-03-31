<?php

namespace App\Http\Controllers;

use App\Repositories\CashActionTypeRepository;
use Illuminate\Http\Request;

class CashActionTypeController extends Controller
{
    private CashActionTypeRepository $instance;

    function __construct(CashActionTypeRepository $instance)
    {
        $this->instance = $instance;
    }

    public function show($code)
    {
        return response()->json([
            "values" => $this->instance->fetchByMode($code),
        ]);
    }

    public function store(Request $request)
    {
        $cat = $this->instance->store($request->all());
        return response()->json([
            "message" => "correctamente guardado",
            "cat" => $cat,
        ]);
    }

    public function update(Request $request, $code)
    {
        $this->instance->update($request->all(), $code);
        return response()->json("Correctamente modificado");
    }
}
