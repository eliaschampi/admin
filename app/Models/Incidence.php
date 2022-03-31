<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incidence extends Model
{
    protected $table = "incidence";

    protected $primaryKey = 'code';

    protected $fillable = [
        "user_code",
        "type",
        "title",
        "description",
        "agreement",
        "created_at",
        "image_attached",
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "user_code");
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, "incidence_student", "incidence_code", "student_dni");
    }
}
