<?php

namespace App\Contract\Hi_FPT;

interface TrackingInterface
{
    public function views($dataTable, $request);
    public function userAnalytics($dataTable, $request);
    public function sessionAnalytics($dataTable, $request);
    public function journeyAnalysis();
}
