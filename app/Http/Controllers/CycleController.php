<?php

namespace App\Http\Controllers;

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
            "values" => $this->instance->fetchCurrentCycles()
        ]);
    }

    public function store(CycleRequest $request)
    {
        try {
            DB::beginTransaction();
            $cycle = $this->instance->store($request->all());
            $s = 1;
            $x = 6;
            switch ($cycle->type) {
                case 'INI':
                    $x = 3;
                    break;
                case 'SEC':
                    $x = 4;
                    break;
                case 'SE5':
                    $s = 5;
                    $x = 5;
                    break;
                case 'GE5':
                    $s = 5;
                    $x = 5;
                    break;
                case 'ADM':
                    $x = 1;
                    break;
            }
            for ($i = $s; $i <= $x; $i++) {
                $cycle->degrees()->create([
                    "code" => $cycle->code . $i,
                    "cycle_code" => $cycle->code,
                ]);
            }
            DB::commit();
            return response()->json("Nivel Académico correctamente aperturado");
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json([
                "res" => $ex->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $code)
    {
        $updated = $this->instance->update($request->all(), $code);
        $code = 200;
        $message = "Nivel educativo ha sido modificado";
        if (is_numeric($updated)) {
            $message = "se finalizó a $updated estudiantes matriculados del nivel";
        }
        return response()->json($message, $code);
    }

    function print($cycle_code)
    {
        /* $cycle = $this->instance->fetchWithSectionsByCode($cycle_code);
        $pdf = \PDF::loadView("reports.cycle", compact("cycle"));
        return $pdf->download("adj.pdf"); */
    }
}
