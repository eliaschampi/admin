<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    protected $table = "inspection";

    protected $primaryKey = "code";

    const UPDATED_AT = null;

    protected $fillable = [
        "inspection_type",
        "user_code",
        "branch_code",
        "entity_type",
        "entity_identifier",
        "description",
        "successfully_completed",
        "additional",
    ];
}
