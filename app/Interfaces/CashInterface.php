<?php

namespace App\Interfaces;

use App\Models\Cash;

interface CashInterface
{
    public function fetchByMonth(string $month);

    public function fetchChart();

    public function surrender(array $data, string $cash_code) : bool;

    public function lastCash(): string;
 
    public function fetch(string $date): ?Cash;
   
    public function openCash(string $cash): Cash;

    public function toggleCash(string $code) : bool;
}