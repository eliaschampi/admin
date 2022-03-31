<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cycle extends Model
{
    protected $table = "cycle";

    protected $primaryKey = 'code';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];

    protected $appends = ['full_name'];

    public function branch()
    {
        return $this->belongsTo(Branch::class, "branch_code");
    }

    public function degrees()
    {
        return $this->hasMany(Degree::class, "cycle_code");
    }

    public function getFullNameAttribute($value)
    {
        return config("main.cycle." . $this->type);
    }
}
