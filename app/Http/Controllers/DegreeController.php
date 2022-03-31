<?php

namespace App\Http\Controllers;

use App\Cache\ConfigCache;
use App\Repositories\DegreeRepository;

class DegreeController extends Controller
{
    protected DegreeRepository $instance;

    public function __construct(DegreeRepository $instance)
    {
        $this->instance = $instance;
    }

    public function index($cycle_code)
    {
        $degrees = ConfigCache::manageDegreeCache($cycle_code, function ($cycle_code) {
            return $this->instance->fetchByCycle($cycle_code);
        });
        return response()->json([
            "values" => $degrees,
        ]);
    }

    public function degree($code)
    {
        $degree = $this->instance->fetchByCode($code);

        if (empty($degree)) {
            return response()->json([
                "message" => "Grado no encontrado",
            ], 404);
        }
        return response()->json([
            "degree" => $degree,
        ]);
    }
}
