<?php

namespace App\Http\Controllers;

use App\Repositories\IncidenceRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class IncidenceController extends Controller
{
    protected IncidenceRepository $instance;

    public function __construct(IncidenceRepository $instance)
    {
        $this->instance = $instance;
    }

    public function fetchByMonth(string $month)
    {
        return response()->json([
            "values" => $this->instance->fetchByMonth($month),
        ]);
    }

    public function store(Request $request)
    {
        $fileHasBeenStored = false;
        $res = json_decode($request->data, true);
        Validator::make($res, [
            "type" => "required",
            "students" => "",
            "title" => "required|max:100",
            "description" => "required|min:10|max:500",
            "agreement" => "required|min:5|max:300",
            "created_at" => "required",
            "is_siseve" => "required"
        ]);
        $filename = "";
        $hasFile = $request->hasFile("file");

        if ($hasFile) {
            $this->validate($request, [
                "file" => "required|file|max:4096",
            ]);
            $file = $request->file("file");
            $ext = $file->getClientOriginalExtension();
            $filename = "ins_" . rand(10000, 99999) . ".$ext";
            $fileHasBeenStored = Storage::putFileAs("/main/", $file, $filename);
        }

        if (!$hasFile || $fileHasBeenStored !== false) {
            $this->instance->store($res, $filename);
            return response()->json([
                "message" => "Correctamente registrado",
            ]);
        }

        return response()->json(false, 500);
    }

    public function update(Request $request, int $code)
    {
        $request->validate([
            "type" => "required",
            "title" => "required|max:100",
            "description" => "required|min:10|max:500",
            "agreement" => "required|min:5|max:300",
            "created_at" => "",
            "is_siseve" => "required"
        ]);
        $this->instance->update($request->all(), $code);
        return response()->json([
            "message" => "Correctamente actualizado",
        ]);
    }

    public function destroy(int $code)
    {
        $incidence = $this->instance->fetchByCode($code);
        $fileHasBeenDeleted = true;

        $attached = $incidence->image_attached;

        if (!empty($attached)) {
            $fileHasBeenDeleted = Storage::delete("/main/" . $attached);
        }

        if ($fileHasBeenDeleted) {
            $this->instance->destroy($incidence);
            return response()->json([
                "message" => "Correctamente eliminado",
            ]);
        }
        return response()->json(false, 500);
    }

    public function downloadAttached(int $code)
    {
        $incidence = $this->instance->fetchByCode($code);
        $filename = $incidence->image_attached;
        if (Storage::exists("/main/" . $filename)) {
            return Storage::download("/main/" . $filename, "adj.pdf", [
                "Content-Type" => "application/pdf",
            ]);
        }
        return response()->json(false, 404);
    }

    public function print($code) {
        $incidence = $this->instance->fetchByCode($code);
        $pdf = \PDF::loadView("pdf.incidence", compact("incidence"));
        return $pdf->download("adj.pdf");
    }

}
