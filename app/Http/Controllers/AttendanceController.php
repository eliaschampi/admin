<?php

namespace App\Http\Controllers;

use App\Cache\CycleCache;
use App\Exports\AttendanceExport;
use Illuminate\Http\Request;
use App\Repositories\AttendanceRepository;
use App\Repositories\PersonRepository;
use App\Repositories\RegisterRepository;
use App\Repositories\TeacherRepository;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    protected AttendanceRepository $instance;

    public function __construct(AttendanceRepository $instance)
    {
        $this->instance = $instance;
    }

    public function fetchBySection(string $section_code, string $date)
    {
        return response()->json([
            "values" => $this->instance->fetchBySectionAndDate($section_code, $date)
        ]);
    }

    public function fetchForTeacherByDate(string $date)
    {
        return response()->json([
            "values" => $this->instance->fetchForTeacherByDate($date)
        ]);
    }

    public function fetchByEntity(string $entity_identifier, string $from_date, string $to_date)
    {
        return response()->json([
            "values" => $this->instance->fetchByEntity($entity_identifier, $from_date, $to_date)
        ]);
    }

    public function fetchAbsences(string $date)
    {
        return response()->json([
            "values" => $this->instance->absences($date)
        ]);
    }

    public function fetchForChart()
    {
        $data = $this->instance->fetchForChart();
        $days = [];
        $count = [];
        foreach ($data as $value) {
            array_push($days, $value->mday);
            array_push($count, $value->count);
        }
        return response()->json([
            "days" => $days,
            "count" => $count,
        ]);
    }

    public function store(Request $request)
    {
        $state = $request->input("state");
        $entity_identifier = $request->input("entity_identifier");
        $entity_type = $request->input("entity_type");
        $time = $state === "falta" || $state === "permiso" ? null : $request->input("entry_time");
        if ($this->instance->todayIsAlreadyRegistered($entity_identifier)) {
            return response()->json("Su asistencia ya ha sido registrado", 402);
        }
        $this->instance->store($entity_identifier, $entity_type, $state, $time);
        if ($state === "tarde" || $state === "falta") {
            //dispatch(new \App\Jobs\SendEmailToFamily($code, $state, $time));
        }
        return response()->json("Correctamente registrado");
    }

    public function auto(Request $request)
    {
        // param is dni
        $dni = $request->input("dni");
        $type = $request->input("type");

        // validate dni
        if (empty($dni)) {

            return response()->json([
                "status" => false,
                "error" => "Su dni es incorrecto"
            ], 422);
        }

        $person = (new PersonRepository)->fetchSingle($dni);

        if (empty($person)) {
            return response()->json([
                "status" => false,
                "message" => "Usuario no encontrado"
            ], 422);
        }

        $cyclevariables = [];
        $register = null;


        if ($type === "s") {
            // fetch current by dni
            $register = (new RegisterRepository)->fetchSingle($request->input("dni"));

            if (empty($register)) {
                return response()->json([
                    "status" => false,
                    "message" => "Este año no está matriculado"
                ], 422);
            }

            // entry time is obtained from the cycle table cached
            $cycle_code = substr($register->section_code, 0, 8);

            $cyclevariables = CycleCache::attendanceVariablesShouldBeCached($cycle_code);
        } else {

            if (!(new TeacherRepository)->teacherIsActive($dni)) {
                return response()->json([
                    "status" => false,
                    "message" => "Docente no habilitado"
                ], 422);
            }

            $cyclevariables = [
                "entry_time" => config("main.entry_time"),
                "tolerance" => config("main.tolerance"),
            ];
        }

        // check if today is already registered
        if ($this->instance->todayIsAlreadyRegistered($dni)) {
            return response()->json([
                "status" => "yet.mp3",
                "register" => $register,
                "person" => $person,
                "message" => "Su asistencia ya ha sido registrado"
            ]);
        }

        $message = "Registrado como ";

        $state = "presente";

        // set state according to the time and entry_time 
        $entry_time = Carbon::createFromFormat("H:i:s", $cyclevariables["entry_time"]);

        if (now()->greaterThanOrEqualTo($entry_time->addMinutes($cyclevariables["tolerance"]))) {
            $state = "tarde";
        }

        // store attendance
        $this->instance->store($dni, $type, $state, date('H:i:s'));


        if ($state === "tarde") {
            // send late notification here
        }

        $message .= $state;

        return response()->json([
            "yup" => "K.E-seps",
            "status" => "ok.mp3",
            "message" => $message,
            "register" => $register,
            "person" => $person
        ]);
    }

    public function update(Request $request, int $code)
    {
        $this->instance->update($request->entry_time, $request->state, $code);
        return response()->json("Correctamente actualizado");
    }

    public function exportToExcel(string $entity_identifier, string $from, string $to)
    {
        return new AttendanceExport($entity_identifier, $from, $to);
    }
}
