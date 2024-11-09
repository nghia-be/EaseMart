<?php

namespace App\Facades;


use App\Contract\Hi_FPT\SettingInterface;
use Illuminate\Support\Facades\Facade;

class SettingFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SettingInterface::class;
    }
}
