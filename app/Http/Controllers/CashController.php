<?php

namespace App\Http\Controllers;

use App\Exports\CashExport;
use App\Interfaces\CashInterface;
use Illuminate\Http\Request;

class CashController extends Controller
{

    protected CashInterface $instance;

    public function __construct(CashInterface $instance)
    {
        $this->instance = $instance;
    }

    public function fetchByMonth($month)
    {
        return response()->json([
            "values" => $this->instance->fetchByMonth($month),
        ]);
    }

    public function surrender(Request $request, $cash_code)
    {
        $this->validate($request, [
            "amount" => "required",
        ]);
        $this->instance->surrender($request->all(), $cash_code);
        return response()->json("OK! Su caja de hoy ha sido rendido");
    }

    public function lastCash()
    {
        return response()->json([
            "cash" => $this->instance->lastCash(),
        ]);
    }

    public function fetch($date = "")
    {
        return response()->json([
            "cash" => $this->instance->fetch($date),
        ]);
    }

    public function fetchChart()
    {
        return response()->json($this->instance->fetchChart());
    }

    public function openCash(Request $request)
    {
        $this->validate($request, [
            "cash" => "required",
        ]);
        $this->instance->openCash($request->cash);
        return response()->json("Ok! Su caja ha sido aperturado");
    }

    public function toggleCash($code)
    {
        $this->instance->toggleCash($code);
        return response()->json("Correctamente actualizado");
    }

    public function exportToExcel()
    {
        return new CashExport();
    }
}
