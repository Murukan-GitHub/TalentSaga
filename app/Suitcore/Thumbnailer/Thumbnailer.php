<?php

namespace Suitcore\Thumbnailer;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Image;
use Suitcore\File\DummyFile;
use Suitcore\Thumbnailer\ImageFile;

class Thumbnailer
{
    use DispatchesJobs;

    protected $file;

    protected $prefix = '_thumb_';

    protected $size = '300x300';

    public function __construct($file = null, $config = [])
    {
        $this->file = $file;

        $this->with($config);
    }

    /**
     * Set config
     * @param  array  $config
     * @return $this
     */
    public function with($config = [])
    {
        $defaults = collect(get_object_vars($this))->except('file')->toArray();

        $configs = array_replace($defaults, config('suitapp.thumbnailer'), $config);

        foreach ($configs as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }

        return $this;
    }

    /**
     * Make Thubnail
     * @param  string  $file path/to/file|File
     * @param  string|array  $size     300x_|_x120|
     * @param  boolean $override delete and create a new one ?
     * @return File    Thumbnail
     */
    public function make($file = null, $size = null, $override = false, $name = null)
    {
        $file = ($file == null) ? $this->file : $file;

        $this->file = new ImageFile($file);

        if (file_exists($location = $this->locateThumbnail($size))) {
            $result = new ImageFile($location);
            if ($name != null) {
                $result = $result->rename($name);
            }
            return $result;
        }

        $this->dispatch((new ThumbnailerJob($file, $size, $override, $name))->onQueue(env('QUEUE_THUMBNAILER_NAME', 'talentsaga-thumbnailer')));

        return $this->file;
    }

    /**
     * Make Thubnail From Queue
     * @param  string  $file path/to/file|File
     * @param  string|array  $size     300x_|_x120|
     * @param  boolean $override delete and create a new one ?
     * @return File    Thumbnail
     */
    public function fromQueue($file = null, $size = null, $override = false, $name = null)
    {
        $file = ($file == null) ? $this->file : $file;

        $size = ($size == null) ? $this->size : $size;

        try {
            $this->file = new ImageFile($file);

            $thumbSize = (array) $size;

            $thumbs = [];

            foreach ($thumbSize as $sz) {
                $result = $this->thumb($sz, $override);
                if ($name != null) {
                    $result = $result->rename($name);
                }
                $thumbs[] = $result;
            }

            return !is_array($size) ? $thumbs[0] : collect($thumbs);
            
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Generate Thubnail
     * @param  string  $size     300x_|_x120|
     * @param  boolean $override delete and create a new one ?
     * @return File    Thumbnail
     */
    protected function thumb($size = null, $override = false)
    {
        // read the image
        try {
        
            $image = Image::make($this->file);
        
        } catch (\Exception $e) {
            
            return new DummyFile;
        }

        if ($this->file && $this->file->getExtension() == 'php') {
            return $this->file;
        }

        $thumbnail = $this->locateThumbnail($size);

        if (file_exists($thumbnail)) {
            
            if (!$override) {
                return new ImageFile($thumbnail);
            }
            
            @unlink($thumbnail);
        }
                
        $image->orientate();

        list($width, $height) = explode('x', $size);

        if (!is_numeric($width) && !is_numeric($height)) {

            return new ImageFile($this->file);
        }

        if (!is_numeric($width)) {

            $image->heighten($height)->save($thumbnail);
        
        } elseif (!is_numeric($height)) {
        
            $image->widen($width)->save($thumbnail);
        
        } else {
        
            $image->fit($width, $height)->save($thumbnail);
        }

        return new ImageFile($thumbnail);
    }

    /**
     * Make Location toThumbnail
     * @param  string $imagePath real path
     * @param  string $width     width
     * @param  string $height    height
     * @return string            real path of thumbnail
     */
    protected function locateThumbnail($size)
    {
        $file = $this->file;

        $filename = pathinfo($file, PATHINFO_FILENAME); // file

        $extension = pathinfo($file, PATHINFO_EXTENSION); // jpg

        $thumbnail = $file->getPath().'/'.$filename. $this->prefix . $size .'.'. $extension;

        return $thumbnail;
    }
}
