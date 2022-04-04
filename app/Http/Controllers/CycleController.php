<?php

namespace App\Http\Controllers;

use App\Cache\CycleCache;
use App\Http\Requests\CycleRequest;
use App\Repositories\CycleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CycleController extends Controller
{
    protected CycleRepository $instance;

    public function __construct(CycleRepository $instance)
    {
        $this->instance = $instance;
    }

    public function index()
    {
        return response()->json([
            "values" => $this->instance->fetchCurrentCycles(),
        ]);
    }

    public function store(CycleRequest $request)
    {
        try {
            DB::beginTransaction();
            $cycle = $this->instance->store($request->all());
            $s = 1;
            $x = 6;

            if ($cycle->type === "SEC") {
                $X = 4;
            } else if (in_array($cycle->type, ["GE5", "OP1", "OR1", "OR2", "IN1", "IN2"])) {
                $s = 5;
                $x = 5;
            } else if ($cycle->type === "ADM") {
                $x = 1;
            }

            for ($i = $s; $i <= $x; $i++) {
                $cycle->degrees()->create([
                    "code" => $cycle->code . $i,
                    "cycle_code" => $cycle->code,
                ]);
            }

            DB::commit();
            return response()->json("Nivel Académico correctamente aperturado");
        } catch (\Exception$ex) {
            DB::rollBack();
            return response()->json([
                "res" => $ex->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $code)
    {
        $this->instance->update($request->all(), $code);
        CycleCache::forgetAttendanceVariables($code);
        return response()->json([
            "message" => "Nivel educativo ha sido modificado",
        ], 200);
    }
}
