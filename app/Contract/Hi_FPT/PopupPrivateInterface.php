<?php

namespace App\Contract\Hi_FPT;

interface PopupPrivateInterface
{
    public const STOP = 0;
    public const ACTIVE = 1;

    public function all($dataTable, $params);

    public function paginate(array $params);

    public function show(array $params);

    public function store(array $params);

    public function update(array $params);

    public function destroy(array $params);

    public function importFile($params);

    public function import(array $params);

}
