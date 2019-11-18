<?php

namespace Suitcore\File;

class DummyFile
{
    public function __call($method, $arguments)
    {
        return $this;
    }

    public function __toString()
    {
        return '';
    }
}
