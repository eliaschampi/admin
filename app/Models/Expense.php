<?php

namespace App\Models;

use App\Helpers\MainHelper;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{

    protected $table = "expense";

    protected $primaryKey = "code";

    protected $fillable = [
        "cashactiontype_code",
        "description",
        "voucher_num",
        "user_code",
        "cash_code",
        "total"
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "user_code");
    }

    public function cash()
    {
        return $this->belongsTo(Cash::class, "cash_code");
    }

    public function actiontype()
    {
        return $this->belongsTo(CashActionType::class, "cashactiontype_code");
    }

    public function detail()
    {
        return $this->hasMany(ExpenseDetail::class, "expense_code");
    }

    public function scopeBranch($query)
    {
        return $query->whereHas("cash", function ($q) {
            $q->where("branch_code", MainHelper::branchCode());
        });
    }
}
