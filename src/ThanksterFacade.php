<?php

namespace Riazxrazor\Thankster;


use Illuminate\Support\Facades\Facade;

class ThanksterFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Thankster::class;
    }
}