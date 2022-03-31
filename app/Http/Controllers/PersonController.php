<?php

namespace App\Http\Controllers;

use App\Helpers\MainHelper;
use App\Repositories\PersonRepository;

class PersonController extends Controller
{
    protected PersonRepository $instance;

    public function __construct(PersonRepository $instance)
    {
        $this->instance = $instance;
    }

    public function show(string $type, string $dni)
    {
        return response()->json([
            "value" => $this->instance->fetch($type, $dni),
        ]);
    }

    public function destroy(string $dni)
    {
        $person = $this->instance->fetchSingle($dni);

        $deletedfile = true;

        if ($person->profile !== null) {
            $deletedfile = MainHelper::deleteImage($person->profile->image);
        }

        $deleted = $this->instance->destroy($person);

        if ($deletedfile === false || $deleted === false) {
            return response()->json("No se ha podido eliminar su imagen", 500);
        }

        return response()->json("Correctamente eliminado");
    }
}
