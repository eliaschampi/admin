<?php

namespace App\Repositories;

class BaseRepository
{

    protected int $branch_code;
    protected int $current_year;
    protected string $user_code;

    function __construct()
    {
        $user = auth()->user();
        if (!empty($user)) {
            $this->branch_code = $user->branch_code;
            $this->current_year = $user->current_year;
            $this->user_code = $user->code;
            unset($user);
        }
    }

    protected function herelike()
    {
        return $this->current_year . "___" . $this->branch_code . "__";
    }

    protected function paginateNumber()
    {
        return 25;
    }
}
