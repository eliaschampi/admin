<?php

namespace App\Interfaces;

use App\Models\Income;

interface IncomeInterface
{
    public function fetchByDates(string $from, string $to);

    public function canceleds();

    public function fetchNewIncomeNumber(string $type);

    public function store(array $data): Income;

    public function canceled(array $data, int $code): bool;
 
}