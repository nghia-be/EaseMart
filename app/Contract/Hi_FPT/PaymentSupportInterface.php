<?php

namespace App\Contract\Hi_FPT;

use App\Repository\RepositoryInterface;
interface PaymentSupportInterface extends RepositoryInterface
{
    public const PROCESSING = 0;
    public const SUCCESS = 1;
    public const CANCEL = 2;
    public function index($dataTable, $dataTableOverview, $params);
    public function update($params, $id);
    public function show($id);
}
