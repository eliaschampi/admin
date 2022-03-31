<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtraInfo extends Model
{
    protected $table = "extrainfo";

    protected $primaryKey = 'student_dni';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'string';

    protected $fillable = [
        "student_dni",
        "religion",
        "livemode",
        "weight",
        "size",
        "live_together",
        "additional",
        "reg_reason",
    ];
}
