<?php

namespace App\Http\Controllers;

use App\Http\Requests\AilmentRequest;
use App\Repositories\AilmentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AilmentController extends Controller
{
   
    protected AilmentRepository $instance;


    public function __construct(AilmentRepository $instance)
    {
        $this->instance = $instance;
    }

    public function show(string $student_dni)
    {
        $values = $this->instance->fetchByStudent($student_dni);
        return response()->json([
            "values" => $values,
        ]);
    }

    public function store(AilmentRequest $request)
    {
        $value = $this->instance->store($request->all());
        return response()->json([
            "message" => "Correctamente guardado",
            "value" => $value,
        ]);
    }

    public function update(AilmentRequest $request, string $code)
    {
        $this->instance->update($request->all(), (int) $code);
        return response()->json([
            "message" => "Correctamente actualizado",
        ]);
    }

    public function uploadAttached(Request $request)
    {
        $code = $request->input("code");
        $file = $request->file("attached");
        $instance = $this->instance->fetchByCode((int) $code);
        $ext = $file->getClientOriginalExtension();
        $fileName = "ailment-" . $instance->student_dni . "-$code.$ext";
        $stored = Storage::putFileAs("/main/", $file, $fileName);
        if ($stored !== false) {
            $this->instance->upload($instance, $fileName);
            return response()->json([
                "message" => "Correctamente actualizado",
            ]);
        }
        return response()->json(false, 500);
    }

    public function destroy(string $code)
    {
        $deleted = false;
        $instance = $this->instance->fetchByCode((int) $code);
        $attached = $instance->attached;
        if (!empty($attached)) {
            $deleted = Storage::delete("/main/" . $attached);
        }
        if (empty($attached) || $deleted) {
            $this->instance->destroy($instance);
            return response()->json([
                "message" => "Correctamente eliminado",
            ]);
        }
        return response()->json(false, 500);
    }

    public function downloadAttached(string $code)
    {
        $ailment = $this->instance->fetchByCode((int) $code);
        if (!empty($ailment->attached)) {
            if (Storage::exists("/main/" . $ailment->attached)) {
                $headers = ["Content-Type" => "application/pdf"];
                return Storage::download("/main/" . $ailment->attached, "adj.pdf", $headers);
            }
        }
        return response()->json(false, 404);
    }




}
