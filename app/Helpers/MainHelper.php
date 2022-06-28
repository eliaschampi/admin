<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MainHelper
{

    public static function branchCode()
    {
        return auth()->user()->branch_code;
    }

    public static function vueGlobalVariables()
    {
        return json_encode([
            'csrfToken' => csrf_token(),
            'appName'  => config('app.name'),
            'version'  => config('app.version'),
            'entry_time'  => config('main.entry_time'),
            'tolerance'  => config('main.tolerance'),
            'apiDomain' => config('app.url') . '/api',
            'secret_key' => config('app.secret_key'),
            'INS' => config('main.ins_name')
        ]);
    }

    public static function lastCode(string $degree_code): string
    {
        $type = substr($degree_code, 4, 3);
        $degree = substr($degree_code, -1);
        $lastyear = (intval(substr($degree_code, 0, 4)) - 1);
        if ($type === "SEC" && $degree === "1") {
            $type = "PRI";
            $degree = "6";
        } else if ($type === "GE5") {
            $type = "SEC";
            $degree = "4";
        } else {
            if ($degree !== "1") {
                $degree = intval($degree) - 1;
            } else {
                $type = "NOT";
            }
        }
        return $lastyear . $type . substr($degree_code, 7, 1) . $degree;
    }

    public static function deleteImage(string $previmg): bool
    {
        $path = env("PROFILE_PATH");
        $disk = env("PROFILE_DISK");
        if (Str::contains($previmg, 'default') === false) {
            return Storage::disk($disk)->delete($path . $previmg);
        }
        return true;
    }

    public static function changeImage(string $previmg, UploadedFile $file, string $dni): string
    {
        $path = env("PROFILE_PATH");
        $disk = env("PROFILE_DISK");
        try {
            if (self::deleteImage($previmg)) {
                $ext = $file->getClientOriginalExtension();
                $fileName = "profile_" . $dni . "_" . rand(100, 999) . "." . $ext;
                Storage::disk($disk)->putFileAs($path, $file, $fileName);
                return $fileName;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function serializeYourModel(Model $model) {
        return $model->toArray();
    }

    public static function to_pg_array($set) {
        settype($set, 'array'); 
        $result = array();
        foreach ($set as $t) {
            if (is_array($t)) {
                $result[] = to_pg_array($t);
            } else {
                $t = str_replace('"', '\\"', $t); 
                if (! is_numeric($t))
                    $t = '"' . $t . '"';
                $result[] = $t;
            }
        }
        return '{' . implode(",", $result) . '}'; 
    }
}
