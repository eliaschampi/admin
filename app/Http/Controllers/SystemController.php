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

    public function printCard(string $id, string $type)
    {
        $person = null;
        $title = "Docente " . date("Y");
        $acode = "NC";
        if ($type === "student") {
            $reg = (new \App\Repositories\RegisterRepository)->fetchByCode($id);
            $person = $reg->student->person;
            $title = substr($reg->section_code, -2) . " de " . $reg->level;
            $acode = substr($reg->code, -4);
        } else {
            $person = (new \App\Repositories\PersonRepository)->fetchSingle($id);
        }

        $pdf = \PDF::loadView("pdf.card", compact("person", "title", "acode"));
        $customPaper = array(0, 0, 153, 244);
        $pdf->setPaper($customPaper, "landscape");
        return $pdf->download("card.pdf");
    }

    public function printCards(string $section_code)
    {
        //TODO FIX IT;
        $persons = (new \App\Repositories\PersonRepository)->fetchForCard($section_code);
        $title = substr($section_code, -2) . " de " . config("main.cycle." . substr($section_code, 4, 3));
        $pdf = \PDF::loadView("pdf.cards", compact("persons", "title"));
        $pdf->setPaper("A4", "portrait");
        return $pdf->download("card.pdf");
    }
}
