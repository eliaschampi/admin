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
        "entity_identifier",
        "branch_code",
        "entity_type",
        "introduction",
        "title",
        "description",
        "conclusion",
        "created_at",
        "file_attached",
        "is_visible"
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "user_code");
    }

    public function person()
    {
        return $this->belongsTo(Person::class, "entity_identifier");
    }
}
