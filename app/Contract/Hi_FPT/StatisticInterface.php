<?php

namespace App\Contract\Hi_FPT;

interface StatisticInterface
{
    public function index($dataTable,$dataTableDetail, $request);
    public function detail($dataTable, $request);
}
