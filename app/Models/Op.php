<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Op extends Model
{
    protected $table = "op";

    protected $primaryKey = "code";

    public $timestamps = false;

    protected $fillable = ["course_code", "teacher_dni", "sts"];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, "teacher_dni");
    }

    public function course()
    {
        return $this->belongsTo(Course::class, "course_code");
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, "op_code");
    }
}
