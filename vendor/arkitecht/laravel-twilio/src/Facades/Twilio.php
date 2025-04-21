<?php

namespace Arkitecht\Twilio\Facades;

use Illuminate\Support\Facades\Facade;

class Twilio extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Arkitecht\Twilio\Twilio::class;
    }
}
