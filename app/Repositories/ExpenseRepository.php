<?php

namespace App\Repositories;

use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class ExpenseRepository extends BaseRepository
{

    public function fetch(int $code): Expense
    {
        return Expense::with(["actiontype", "detail"])->findOrFail($code);
    }

    public function fetchByDates(string $from, string $to)
    {
        return Expense::branch()->whereBetween("created_at", [$from, $to])
            ->with("actiontype")
            ->latest()
            ->get();
    }

    public function fetchGroupedByType(string $from, string $to)
    {
        return Expense::branch()->whereBetween("created_at", [$from, $to])
            ->select("cashactiontype.name", DB::raw("sum(total)"))
            ->join("cashactiontype", "cashactiontype.code", "expense.cashactiontype_code")
            ->groupBy("cashactiontype.name")->pluck("sum", "name");
    }

    public function store(array $data): Expense
    {
        return DB::transaction(function () use ($data) {
            $data["user_code"] = $this->user_code;
            $expense = Expense::create($data);
            foreach ($data["detail"] as $item) {
                $expense->detail()->create($item);
            }
            return $expense;
        });
    }

    public function update(array $data, $code): Expense
    {
        return DB::transaction(function () use ($data, $code) {
            $expense = Expense::find($code);
            $expense->cashactiontype_code = $data["cashactiontype_code"];
            $expense->description = $data["description"];
            $expense->voucher_num = $data["voucher_num"];
            $expense->total = $data["total"];
            if ($expense->save()) {
                foreach ($data["detail"] as $item) {
                    if (empty($item["code"])) { //solo agrega no borra
                        $expense->detail()->create($item);
                    }
                }
            }
            return $expense;
        });
    }

    public function destroy(int $code): bool
    {
        $expense = Expense::find($code);
        return $expense->delete();
    }
}
