<?php

namespace Suitcore\Emailer\Facades;

class Email extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'emailer';
    }
}
