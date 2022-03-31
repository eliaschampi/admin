<?php

namespace App\Repositories;

use App\Helpers\MainHelper;
use App\Models\Cycle;
use App\Models\Register;

class CycleRepository extends BaseRepository
{

    public function fetchByCode(string $code): Cycle
    {
        return Cycle::findOrFail($code);
    }

    public static function fetchAttendaceVariablesByCode(string $code)
    {
        $cycle = Cycle::select("entry_time", "tolerance")->find($code);
        //serialize
        return MainHelper::serializeYourModel($cycle);
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

        if (now()->lte(\Carbon\Carbon::parse($cycle->to))) {

            $cycle->from = $cycledata["from"];
            $cycle->to = $cycledata["to"];
            $cycle->entry_time = $cycledata["entry_time"];
            $cycle->tolerance = $cycledata["tolerance"];
            $cycle->monthly = $cycledata["monthly"];
            return $cycle->save();
        } else {

            $cycle_code = $cycle->code;
            return Register::whereHas("section.degree", function ($query) use ($cycle_code) {
                $query->where("cycle_code", $cycle_code);
            })->update(array("state" => "f"));
        }
    }
}
