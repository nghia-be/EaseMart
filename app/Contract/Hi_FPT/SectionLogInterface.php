<?php

namespace App\Contract\Hi_FPT;

interface SectionLogInterface
{
    public function all($dataTable, $params);

    public function show($id);

    public function store($params);
}
