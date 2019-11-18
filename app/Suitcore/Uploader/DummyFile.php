<?php

namespace Suitcore\Uploader;

class DummyFile
{
    public function __call($method, $arguments)
    {
        return $this;
    }
}
