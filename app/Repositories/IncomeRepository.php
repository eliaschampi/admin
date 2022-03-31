<?php

namespace App\Repositories;

use App\Interfaces\IncomeInterface;
use App\Models\Income;
use Illuminate\Support\Facades\DB;

class IncomeRepository extends BaseRepository implements IncomeInterface
{

    public function fetchByDates(string $from, string $to)
    {
        return Income::branch()->with(["user" => function ($query) {
            $query->select("code", "name");
        }])->whereBetween("created_at", [$from, $to])
            ->whereState(true)
            ->latest()
            ->get()
            ->each->setAppends(["name", "serie"]);
    }

    public function canceleds()
    {
        return Income::branch()->with("canceled")
            ->whereState(false)
            ->whereYear("created_at", $this->current_year)
            ->latest()
            ->get()
            ->each->setAppends(["name", "serie"]);
    }

    public function fetchNewIncomeNumber(string $type)
    {
        return DB::selectOne("select * from usp_maxNumberInvoice(?,?)", [$type, $this->branch_code]);
    }

    public function store(array $data): Income
    {

        $incomeData = $data;
        $incomeData["user_code"] = $this->user_code;
        $income = Income::create($incomeData);

        $customer = $data["customer_identifier"];

        if ($data["mod"] === "student") {
            $income->hasRegister()->create([
                "register_code" => $customer
            ]);
        } else {
            if (empty($customer["code"])) {
                $income->hasCustomer()->create([
                    "name" => $customer["name"],
                ]);
            } else {
                $income->hasCustomer()->create([
                    "customer_code" => $customer["code"],
                ]);
            }
        }
        return $income;
    }

    public function canceled(array $data, int $code): bool
    {
        $income = Income::find($code);
        $income->state = false;
        $income->canceled()->create([
            "justification" => $data["justification"]
        ]);
        return $income->save();
    }
}
