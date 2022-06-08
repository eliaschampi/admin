<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SystemController extends Controller
{

    public function upload(Request $request)
    {
        $request->validate([
            "image" => "required|file|mimes:png|max:1024",
            "name" => "required",
        ]);
        $file = $request->file("image");
        $fileName = $request->input("name") . "." . $file->getClientOriginalExtension();
        $stored = Storage::disk("public")->putFileAs("/profile/", $file, $fileName);
        if ($stored !== false) {
            return response()->json("Correctamente modificado");
        }
        return response()->json(false, 500);
    }

    public function ubigeo()
    {
        return response()->json([
            "values" => json_decode(file_get_contents(storage_path() . "/app/ubigeo.json"), true),
        ]);
    }

    public function counts()
    {
        $s_count = (new \App\Repositories\StudentRepository())->fetchCountByBranch();
        $t_count = (new \App\Repositories\TeacherRepository())->fetchCountByBranch();
        $r_count = (new \App\Repositories\RegisterRepository())->fetchCountByBranch();
        $f_count = (new \App\Repositories\FamilyRepository())->fetchCountByBranch();
        return response()->json([
            "s_count" => $s_count,
            "t_count" => $t_count,
            "r_count" => $r_count,
            "f_count" => $f_count,
        ]);
    }

    public function sendMessageToElias(Request $request)
    {
        \DB::table("support")->insert([
            "type" => "admin",
            "from_dni" => $request->input("dni"),
            "content" => $request->input("content"),
        ]);
        return response()->json("Tu mensaje se envió con éxito");
    }

    public function printCard(string $mode, string $identifier, string $subtitle = "")
    {
        $personInstance = new \App\Repositories\PersonRepository();

        $persons = [];
        $title = "Carrión 2022";

        if ($mode === "student") {

            $register = $personInstance->fetchSingle($identifier);
            array_push($persons, $register->student->person);
            $title = $subtitle;

        } else if ($mode === "section") {

            $persons = $personInstance->fetchForCard($identifier);
            $title = substr($identifier, -2) . " de " . config("main.cycle." . substr($identifier, 4, 3));

        } else if ($mode === "teacher") {

            $persons = $personInstance->fetchForCardT();
            $title = $mode;

        }
        $pdf = \PDF::loadView("pdf.card", compact("persons", "title"));
        $pdf->setPaper("A4", "portrait");
        return $pdf->download("card.pdf");
    }
}
