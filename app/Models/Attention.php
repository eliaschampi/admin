<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attention extends Model
{
    protected $table = "attention";

    protected $primaryKey = 'code';

    protected $fillable = [
        "user_code",
        "type",
        "person_dni",
        "branch_code",
        "person_type",
        "introduction",
        "title",
        "description",
        "conclusion",
        "created_at",
        "file_attached",
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "user_code");
    }

    public function person()
    {
        return $this->belongsTo(Person::class, "person_dni");
    }
}
