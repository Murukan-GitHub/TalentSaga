<?php

namespace Suitcore\FileGrabber\Facades;

class FileGrab extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'filegrab';
    }
}
