<?php

namespace Suitcore\File;

use Symfony\Component\HttpFoundation\File\File as FileSystem;
use Suitcore\File\Contracts\FileInterface as FileContract;

class File extends FileSystem implements FileContract
{
    protected $uploader;

    /**
     * Save the file as different name in same directory
     * @param  string $name obviously
     * @return File
     */
    public function saveAs($name)
    {
        if (! file_exists($this)) {
            return false;
        }

        return $this->move($this->getPath(), $name);
    }

    /**
     * Save to different disk
     * @param  string $disk local|s3|rackspace|etc
     * @param  string $path path/to/file
     * @return int
     */
    public function to($disk, $path)
    {
        return Storage::disk($disk)->put($path, file_get_contents($this));
    }

    /**
     * Delete this file
     * @return bool true|false in failure
     */
    public function delete()
    {
        return @unlink($this);
    }

    public function rename($newName)
    {
        $name = $newName.'.'.$this->getExtension();
        return $this->saveAs($name);
    }

    // Available API
    // API
    // getATime
    // getBasename
    // getCTime
    // getExtension
    // getFileInfo
    // getFilename
    // getGroup
    // getInode
    // getLinkTarget
    // getMTime
    // getOwner
    // getPath
    // getPathInfo
    // getPathname
    // getPerms
    // getRealPath
    // getSize
    // getType
    // isDir
    // isExecutable
    // isFile
    // isLink
    // isReadable
    // isWritable
    // openFile
    // setFileClass
    // setInfoClass
}
