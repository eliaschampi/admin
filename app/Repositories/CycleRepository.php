<?php

namespace App\Repositories;

use App\Helpers\MainHelper;
use App\Models\Cycle;

class CycleRepository extends BaseRepository
{

    public function fetchByCode(string $code): Cycle
    {
        return Cycle::findOrFail($code);
    }

    public static function fetchAttendaceVariablesByCode(string $code)
    {
        $cycle = Cycle::select("attendance")->find($code);

        if (!empty($cycle)) {
            $items = $cycle->attendance;
            return $items;
        }
        return [];
    }

    public function fetchCurrentCycles()
    {
        return Cycle::whereYear("created_at", $this->current_year)
            ->where("branch_code", $this->branch_code)
            ->orderBy("type")
            ->get();
    }

    public function store(array $cycle): Cycle
    {
        $cycle["code"] = date('Y') . $cycle["type"] . $this->branch_code;
        $cycle["branch_code"] = $this->branch_code;
        return Cycle::create($cycle);
    }

    public function update(array $cycledata, string $code)
    {
        $cycle = $this->fetchByCode($code);
        $cycle->from = $cycledata["from"];
        $cycle->to = $cycledata["to"];
        $cycle->monthly = $cycledata["monthly"];
        $cycle->attendance = $cycledata["attendance"];
        return $cycle->save();
    }
}
