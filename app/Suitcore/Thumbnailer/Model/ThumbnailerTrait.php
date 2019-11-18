<?php

namespace Suitcore\Thumbnailer\Model;

use Image;
use Thumb;
use Exception;

/**
 * HOW TO USE
 * insert trait to whenever model
 * or you can specify the default width and height in your base model
 *
 *
 * protected defWidth = 480 // landscape
 * protected defHeight = 360 // portrait
 * protected $thumbnailStyle = ['small_square' => '128x128', 'medium_square' => '256x256',
 * 'large_square' => '512x512', 'small_cover' => '240x_', 'medium_cover' => '480x_', 'large_cover' => '1024x_'];
 * protected imageAtrributes = ['your_image', ...]
 * protected defThumbnail = '_thumbnail'
 * protected baseFolder = 'public/uploads'
 */

trait ThumbnailerTrait
{
    protected static $oldThumbnail;

    // every images must created the thumbnail
    public static function bootThumbnailerTrait()
    {
        static::saving(function ($model) {
            $model::$oldThumbnail = $model->getOriginal();
        });
        static::saved(function ($model) {
            $model->makeThumbnail();
        });
        static::deleted(function ($model) {
            $model->deleteThumbnails();
        });
    }

    public function toArray()
    {
        $array = array_merge(parent::toArray(), $this->getThumbnailArray());

        return $array;
    }

    public function getAttribute($key)
    {
        $array = [];

        try {
            $array = $this->getThumbnailArray();
        } catch (\Exception $e) {
            //
        }

        if (array_key_exists($key, $array)) {
            return $array[$key];
        }
        
        return parent::getAttribute($key);
    }

    public function deleteThumbnails()
    {
        $originals = $this->getOriginal();

        foreach ($this->getImageAttributes() as $attribute => $folder) {
            
            if (!isset($originals[$attribute])) {
                continue;
            }

            $image = $this->getImagePath($attribute);

            @unlink($image);

            $this->deleteThumbnailFromImage($image);

        }
    }

    public function deleteThumbnailFromImage($filepath)
    {
        // read style
        foreach (array_merge($this->thumbnailStyle, [$this->defThumbnailName => '']) as $value) {
            @unlink($this->locateThumbnail($filepath, $value));
        }
    }

    /**
     * Determines if an array is associative.
     *
     * An array is "associative" if it doesn't have sequential numerical keys beginning with zero.
     *
     * @param  array  $array
     * @return bool
     */
    public function isAssoc(array $array)
    {
        $keys = array_keys($array);

        return array_keys($keys) !== $keys;
    }

    protected function getImageFolder($attribute)
    {
        $imageAttributes = $this->imageAttributes;
        $folder = !$this->isAssoc($imageAttributes) ? '' :
            (isset($imageAttributes[$attribute]) ? $imageAttributes[$attribute] : '');
        
        return $folder;
    }

    public function getImagePath($attribute, $file = null)
    {
        if ($file == null) {
            $file = isset($this->attributes[$attribute]) ? $this->attributes[$attribute] : '';
        }

        return base_path($this->baseFolder.'/'.$this->getImageFolder($attribute).'/'.$file);
    }

    protected function getImageAttributes()
    {
        $imageAttributes = $this->imageAttributes;

        if (!$this->isAssoc($imageAttributes)) {
            $imageAttributes = array_flip($imageAttributes);
        }

        return $imageAttributes;
    }

    public function makeThumbnail()
    {
        foreach ($this->getImageAttributes() as $attribute => $folder) {
            
            if (!$this->getAttributeValue($attribute)) {
                continue;
            }

            $this->generateThumbnail($attribute);
        }
    }

    protected function getThumbnailName($file, $size = '')
    {
        return pathinfo(basename($file), PATHINFO_FILENAME).$this->defThumbnailName.$size;
    }

    protected function getExtension($file)
    {
        return pathinfo(basename($file), PATHINFO_EXTENSION);
    }

    protected function generateThumbnail($attribute, $size = null)
    {
        $savedOldThumbnail = isset(static::$oldThumbnail[$attribute]) ? static::$oldThumbnail[$attribute] : null;

        $imagePath = $this->getImagePath($attribute);

        $oldThumbnail = $this->getImagePath($attribute, $savedOldThumbnail);

        if (!file_exists($oldThumbnail) && $this->isDirty($attribute)) {

            @unlink($oldThumbnail);

            $this->deleteThumbnailFromImage($oldThumbnail);
        }


        if (!file_exists($imagePath)) {
            return;
        }

        // set default thumbnail
        $size = $size == null ? implode('x', [$this->defWidth, $this->defHeight]) : $size;

        $thumbnail = $this->getThumbnailName($imagePath); // file._thumbnail

        try {
            Thumb::make($imagePath, $size, false, $thumbnail);

            // read style
            foreach ($this->thumbnailStyle as $size) {
                $name = $this->getThumbnailName($imagePath, $size);
                Thumb::make($imagePath, $size, false, $name);
            }
        } catch (\Exception $e) {
            //
        }
    }

    /**
     * Make Location toThumbnail
     * @param  string $imagePath real path
     * @param  string $width     width
     * @param  string $height    height
     * @return string            real path of thumbnail
     */
    protected function locateThumbnail($imagePath = '', $size = '')
    {
        $expl = explode('.', $imagePath);
        $extension = array_pop($expl);

        $thumbnail = implode('.', $expl). $this->defThumbnailName . $size .'.'. $extension;

        return $thumbnail;
    }
    
    // make a function to access the thumbnail

    public function getThumbnailArray()
    {
        $array = [];

        foreach ($this->imageAttributes as $attribute => $imageDir) {
            
            // if (is_numeric($attribute)) {
            //     $attribute = $imageDir;
            //     $imageDir = '';
            // }
            try {
                if (parent::getAttribute($attribute) == null) {
                    continue;
                }
            } catch(Exception $e) { 
                // dd(parent::getAttribute($attribute));
            }

            $imagePath = $this->getImagePath($attribute);
            
            $array[$attribute] = url(substr($imagePath, strlen(public_path())));
            
            if (!file_exists($imagePath)) {
                continue;
            }

            $style = array_merge($this->thumbnailStyle, [ltrim($this->defThumbnailName, '_') => null]);

            foreach ($style as $name => $size) {

                // $attributeName = str_replace('_url', '', $attribute).'_'.$name;
                $attributeName = $attribute . '_' .$name;

                if (!isset($this->attributes[$attribute]) || $this->attributes[$attribute] == null) {
                    $array[$attributeName] = null;
                    continue;
                }
                
                
                $thumbnail = $this->locateThumbnail($imagePath, $size);

                if (!file_exists($thumbnail)) {
                    // $this->generateThumbnail($attribute, $size);
                    $array[$attributeName] = $array[$attribute];
                    continue;
                }

                $array[$attributeName] = url(substr($thumbnail, strlen(public_path())));
            }

        }

        return $array;
    }
}
