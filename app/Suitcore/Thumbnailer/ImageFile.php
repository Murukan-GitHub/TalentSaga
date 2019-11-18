<?php

namespace Suitcore\Thumbnailer;

use Image;
use Suitcore\File\File as FileSystem;

class ImageFile extends FileSystem
{
    public function __call($method, $arguments)
    {
        if (method_exists($image = Image::make($this), $method)) {

            $args = array_shift(func_get_args());

            call_user_func_array(array($image, $method), $args);
        }
    }
}
