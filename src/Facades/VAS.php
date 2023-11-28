<?php

namespace EdLugz\VAS\Facades;

use Illuminate\Support\Facades\Facade;

class VAS extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'vas';
    }
}
