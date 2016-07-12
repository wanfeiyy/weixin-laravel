<?php
namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class ValidatorResultClass extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'ValidatorResult';
    }
}