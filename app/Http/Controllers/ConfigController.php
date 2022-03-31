<?php

namespace App\Http\Controllers;

use App\Repositories\ConfigRepository;
use Illuminate\Http\Request;

class ConfigController extends Controller
{

    protected ConfigRepository $instance;

    public function __construct(ConfigRepository $instance)
    {
        $this->instance = $instance;
    }

    public function tags($tags)
    {
        return response()->json([
            "configs" => $this->instance->fetchByTags(explode("_", $tags))
        ]);
    }

    public function tagsPlucked($tags)
    {
        return response()->json([
            "configs" => $this->instance->fetchByTagsPlucked(explode("_", $tags))
        ]);
    }

    public function update(Request $request, $code)
    {
        $this->instance->update($request->value, $code);
        return response()->json("Correctamente modificado");
    }

    public function updateMany(Request $request)
    {
        $this->instance->updateMany($request->data);
        return response()->json("Correctamente modificado");
    }
}
