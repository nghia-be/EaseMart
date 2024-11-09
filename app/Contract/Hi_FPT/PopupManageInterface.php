<?php

namespace App\Contract\Hi_FPT;

interface PopupManageInterface
{
    public function all($dataTable, $params);

    public function show($dataTable, $id);

    public function store(array $params);

    public function push(array $params);

    public function detail($id);

    public function getDetailPersonalMaps($id);
    public function export_click_phone($params, $id);

}
