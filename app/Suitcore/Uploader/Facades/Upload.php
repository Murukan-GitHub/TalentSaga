<?php

namespace Suitcore\Uploader\Facades;

class Upload extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'upload';
    }
}
