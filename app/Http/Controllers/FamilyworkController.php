<?php

namespace App\Http\Controllers;

use App\Repositories\FamilyworkRepository;
use Illuminate\Http\Request;

class FamilyworkController extends Controller
{
    protected FamilyworkRepository $instance;

    public function __construct(FamilyworkRepository $instance)
    {
        $this->instance = $instance;
    }

    public function show(string $family_dni)
    {
        return response()->json([
            "value" => $this->instance->fetch($family_dni)
        ]);
    }

    public function store(Request $request)
    {
        $status = $this->instance->upsert((object) $request->all());
        return response()->json([
            "message" => "correctamente guardado",
            "issaved" => $status,
        ]);
    }
}
