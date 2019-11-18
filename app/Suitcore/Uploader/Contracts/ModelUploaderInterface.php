<?php

namespace Suitcore\Uploader\Contracts;

interface ModelUploaderInterface
{
    public function getFields();

    public function setFile($field, $file);

    public function deleteFile($field, $file);
}
