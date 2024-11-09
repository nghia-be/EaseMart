<?php

namespace App\Contract\Hi_FPT;

interface FtelPhoneInterface
{
    public function all($dataTable, $params);

    public function create($dataTable, $params);

    public function update($params, $id);
}
