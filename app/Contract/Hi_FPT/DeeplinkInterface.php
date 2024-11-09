<?php

namespace App\Contract\Hi_FPT;

interface DeeplinkInterface
{
    public function index($dataTable, $params);

    public function show($id);

    public function create();

    public function store($params);

    public function update($params, $id);

    public function delete($params);
}
