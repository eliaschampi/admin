<?php

namespace App\Http\Controllers;

use App\Decorators\CashDecorator;
use App\Exports\ExpenseExport;
use App\Http\Requests\ExpenseRequest;
use App\Repositories\ExpenseRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{

    private ExpenseRepository $instance;


    function __construct(ExpenseRepository $instance)
    {
        $this->instance = $instance;
    }

    public function show(int $code)
    {
        return response()->json([
            "expense" => $this->instance->fetch($code)
        ]);
    }

    public function fetchByDates(string $from, string $to)
    {
        return response()->json([
            "values" => $this->instance->fetchByDates($from, $to)
        ]);
    }

    public function fetchGroupedByType(string $from, string $to)
    {
        return response()->json($this->instance->fetchGroupedByType($from, $to));
    }

    public function store(ExpenseRequest $request)
    {
        $this->instance->store($request->all());
        CashDecorator::forgetCache();
        return response()->json("Correctamente guardado");
    }

    public function update(Request $request, int $code)
    {
        $expense = $this->instance->update($request->all(), $code);
        if (Carbon::parse($expense->created_at)->isToday()) {
            CashDecorator::forgetCache();
        }
        return response()->json("Correctamente actualizado");
    }

    public function destroy(int $code)
    {
        $this->instance->destroy($code);
        CashDecorator::forgetCache();
        return response()->json("Ok! Un egreso ha sido eliminado");
    }

    public function print(int $code)
    {
        $expense = $this->instance->fetch($code);
        $pdf = \PDF::loadView("pdf.expense", compact("expense"));
        return $pdf->download("adj.pdf"); 
    }

    public function exportToExcel(string $from, string $to)
    {
        return (new ExpenseExport($from, $to));
    }
}
