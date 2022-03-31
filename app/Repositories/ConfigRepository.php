<?php

namespace App\Repositories;

use App\Models\Config;

class ConfigRepository
{

    public function fetchByCode(string $code)
    {
        return Config::find($code)->value;
    }

    public function fetchByTags(array $tags)
    {
        return Config::whereIn("tag", $tags)->get();
    }

    public function fetchByTagsPlucked(array $tags)
    {
        return Config::whereIn("tag", $tags)->pluck("value", "code");
    }

    public function update(string $value, string $code)
    {
        $config = Config::find($code);
        $config->value = $value;
        return $config->save() ? $config->tag : false;
    }

    public function updateMany(array $configs): int
    {
        $hasBeenModified = 0;
        foreach ($configs as $config) {
            $company = Config::find($config["code"]);
            $company->value = $config["value"];
            if ($company->save()) {
                $hasBeenModified++;
            }
        }
        return $hasBeenModified;
    }
}
