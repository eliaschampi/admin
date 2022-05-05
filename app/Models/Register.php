<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Register extends Model
{
    protected $table = "register";

    protected $primaryKey = "code";

    public $incrementing = false;

    const UPDATED_AT = null;

    protected $keyType = 'string';

    protected $appends = ["level", "year"];

    protected $fillable = [
        "code",
        "section_code",
        "student_dni",
        "state",
        "monthly",
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, "student_dni");
    }


    public function section()
    {
        return $this->belongsTo(Section::class, "section_code");
    }

    public function getLevelAttribute($value)
    {
        return config("main.cycle." . substr($this->section_code, 4, 3));
    }

    public function getYearAttribute($value)
    {
        return substr($this->section_code, 0, 4);
    }
}
