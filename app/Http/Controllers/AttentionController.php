<?php

namespace App\Http\Controllers;

use App\Repositories\AttentionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
class AttentionController extends Controller
{
    protected AttentionRepository $instance;

    public function __construct(AttentionRepository $instance)
    {
        $this->instance = $instance;
    }

    public function fetchByMonth(string $month)
    {
        return response()->json([
            "values" => $this->instance->fetchByMonth($month),
        ]);
    }

    public function fetchByEntity(string $dni, string $show_all = "false")
    {
        return response()->json([
            "values" => $this->instance->fetchByEntity($dni, $show_all === "true"),
        ]);
    }

    public function store(Request $request)
    {
        $fileHasBeenStored = false;
        $res = json_decode($request->data, true);
        Validator::make($res, [
            "entity_type" => "required",
            "entity_identifier" => "required",
            "type" => "required",
            "title" => "required|min:5|max:80",
            "introduction" => "required|min:10|max:300",
            "description" => "required|min:10|max:500",
            "conclusion" => "nullable|min:5|max:200",
            "created_at" => "required",
            "is_visible" => "required",
        ]);
        $filename = "";
        $hasFile = $request->hasFile("file");

        if ($hasFile) {
            $this->validate($request, [
                "file" => "required|file|max:4096",
            ]);
            $file = $request->file("file");
            $ext = $file->getClientOriginalExtension();
            $filename = "at_" . $res["entity_identifier"] . "_" . rand(10000, 99999) . ".$ext";
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
            "entity_identifier" => "required",
            "type" => "required",
            "title" => "required",
            "introduction" => "required|min:10|max:300",
            "description" => "required|min:10|max:500",
            "conclusion" => "nullable|min:5|max:200",
            "created_at" => "",
            "is_visible" => "required",
        ]);
        $this->instance->update($request->all(), $code);
        return response()->json([
            "message" => "Correctamente actualizado",
        ]);
    }

    public function destroy(int $code)
    {
        $attention = $this->instance->fetchByCode($code);
        $fileHasBeenDeleted = true;

        $attached = $attention->file_attached;

        if (!empty($attached) && Storage::exists("/main/" . $attached)) {
            $fileHasBeenDeleted = Storage::delete("/main/" . $attached);
        }

        if ($fileHasBeenDeleted) {
            $this->instance->destroy($attention);
            return response()->json([
                "message" => "Correctamente eliminado",
            ]);
        }
        return response()->json(false, 500);
    }

    public function downloadAttached($code)
    {
        $attention = $this->instance->fetchByCode($code);
        $filename = $attention->file_attached;
        if (Storage::exists("/main/" . $filename)) {
            return Storage::download("/main/" . $filename, "adj.pdf", [
                "Content-Type" => "application/pdf",
            ]);
        }
        return response()->json(false, 404);
    }

    function print($code) {
        $attention = $this->instance->fetchByCode($code);
        $pdf = \PDF::loadView("pdf.attention", compact("attention"));
        $pdf->setPaper("A4", "portrait");
        return $pdf->download("adj.pdf");
    }
}
