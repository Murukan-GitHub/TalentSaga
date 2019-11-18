<?php

namespace Suitcore\Uploader;

use Storage;
use Suitcore\File\File as FileSystem;
use Suitcore\Uploader\Upload as Uploader;

class File extends FileSystem
{
    protected $uploader;

    public function __construct(Uploader $file)
    {
        $this->uploader = $file;

        parent::__construct($file->getFile());
    }

    /**
     * Get Uploader Property
     * @return Uploader obviously
     */
    public function getUploader()
    {
        return $this->uploader;
    }

    /**
     * Save to different disk
     * @param  string $disk local|s3|rackspace|etc
     * @param  string $path path/to/file
     * @return int
     */
    public function to($disk, $path = null)
    {
        $path = $path === null ? $this->uploader->getFolder().'/'.$this->getBasename() : $path;

        return parent::to($disk, $path);
    }
}
