<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ailment extends Model
{
    protected $table = "ailment";

    protected $primaryKey = 'code';

    public $timestamps = false;

    protected $fillable = [
        "student_dni",
        "type",
        "ailment",
        "cause",
        "treatment",
        "attached",
    ];
}
