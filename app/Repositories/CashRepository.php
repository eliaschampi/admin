<?php

namespace App\Repositories;


use App\Interfaces\CashInterface;
use App\Models\Cash;
use Illuminate\Support\Facades\DB;

class CashRepository extends BaseRepository implements CashInterface
{
    public function fetchByMonth(string $month)
    {
        $cash = Cash::here()
            ->withCount(['incomes as isum' => function ($query) {
                $query->select(DB::raw('sum(total)'));
            }, 'expenses as esum' => function ($query) {
                $query->select(DB::raw('sum(total)'));
            }])
            ->with(["surrendered", "user" => function ($query) {
                $query->select("code", "name");
            }]);

        if ($month === "all") {
            return $cash->whereYear("created_at", $this->current_year)
                ->orderBy("created_at", "desc")->get();
        }

        return $cash
            ->whereYear("created_at", $this->current_year)
            ->whereMonth("created_at", $month)
            ->latest()->get();
    }

    public function fetchChart()
    {

        return Cash::select(
            DB::raw("date_part('year', created_at) as myear, date_part('month', created_at) as month, sum(surrendered.amount)")
        )
            ->join("surrendered", "code", "surrendered.cash_code")
            ->havingRaw("branch_code = ? and date_part('year', created_at) = ?", [
                $this->branch_code,
                $this->current_year
            ])
            ->groupBy("myear", "branch_code", "month")
            ->orderBy("month")
            ->get();
    }

    public function surrender(array $data, string $cash_code): bool
    {
        $cash = Cash::find($cash_code);
        $cash->surrendered()->create($data);
        $cash->cash -= $data["amount"];
        return $cash->save();
    }

    public function lastCash(): string
    {
        return Cash::here()->latest()->first(["cash"])->cash;
    }

    public function fetch(string $date): ?Cash
    {
        return Cash::withCount(['incomes as isum' => function ($query) {
            $query->select(DB::raw('sum(total)'));
        }, 'expenses as esum' => function ($query) {
            $query->select(DB::raw('sum(total)'));
        }, 'incomes', 'expenses'])
            ->with(["surrendered", "user" => function ($query) {
                $query->select("code", "name");
            }])
            ->here()->whereDate("created_at", $date)
            ->first();
    }

    public function openCash(string $cash): Cash
    {
        $date = now();
        $cash_code = "CJ" . $this->branch_code;
        $cash_code .= substr($date->year, -2) . "-";
        $cash_code .= str_pad($date->month, 2, "0", STR_PAD_LEFT);
        $cash_code .= str_pad($date->day, 2, "0", STR_PAD_LEFT);

        return Cash::create([
            "code" => $cash_code,
            "branch_code" => $this->branch_code,
            "user_code" => $this->user_code,
            "cash" => $cash,
        ]);
    }

    public function toggleCash(string $code): bool
    {
        $cash = Cash::find($code);
        $cash->state = !$cash->state;
        return $cash->save();
    }
}
