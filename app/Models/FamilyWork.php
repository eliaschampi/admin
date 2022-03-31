<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyWork extends Model
{
    protected $table = "familywork";

    protected $primaryKey = 'family_dni';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'string';
}
