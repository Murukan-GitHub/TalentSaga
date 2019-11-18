<?php

namespace Suitcore\File\Contracts;

interface FileInterface
{
    public function saveAs($name);

    public function to($disk, $path);

    public function delete();
}
