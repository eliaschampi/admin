<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = "course";

    protected $primaryKey = 'code';

    public $timestamps = false;

    protected $fillable  = ["name", "type"];
}
