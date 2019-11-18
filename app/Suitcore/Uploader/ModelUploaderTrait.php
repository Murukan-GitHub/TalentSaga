<?php

namespace Suitcore\Uploader;

use Suitcore\File\File;

trait ModelUploaderTrait
{
    // protected $baseFolder = 'public/uploads'
    // protected $folder = ''
    // protected $onlyFileName = true
    // protected $files = ['field' => ''folder', ..]

    /**
     * Get Fields that contains files
     * @return array of fields
     */
    public function getFields()
    {
        $fields = $this->getFiles();
        
        // if associative array => $files = ['field' => ''folder', ..]
        if (array_values($fields) != $fields && $fields != []) {
            $fields = array_keys($fields);
        }

        return $fields;
    }

    /**
     * Set value to input string represents file based on respective config for each field
     * @param string $field field name
     * @param File   $file
     *
     * @return void
     */
    public function setFile($field, $file)
    {
        $folder = $this->getFolderField($field);

        // if ($this->getFilePath($field, $file) != $file->getRealPath()) {
        //     return;
        // }
        
        $value = ($this->isOnlyFileName()) ? $file->getBasename() : $folder.'/'.$file->getBasename();
        
        $this->attributes[$field] = $value;
    }

    /**
     * Set value to input string represents file based on respective config for each field
     * @param string $field field name
     * @param File   $file
     *
     * @return void
     */
    public function getFile($field)
    {
        $file = $this->getOriginal($field);

        return $this->getFilePath($field, $file);
    }


    /**
     * Delete file based on respective config of field
     * @param  string $field
     * @param  File $file
     * @return boolean  true|false when failure
     */
    public function deleteFile($field, $file)
    {
        $deleteFile = $this->getFilePath($field, $file);

        return @unlink($deleteFile);
    }

    /**
     * Get base folder
     * @return string base/path
     */
    protected function getBaseFolder()
    {
        return property_exists($this, 'baseFolder') ? $this->baseFolder : '';
    }

    protected function getFiles()
    {
        return
                property_exists($this, 'files') ? $this->files :
                    (property_exists($this, 'imageAttributes') ? $this->imageAttributes : []);
    }

    /**
     * Get folder name
     * @param  string $field
     * @return string
     */
    protected function getFolderField($field)
    {
        $folder = property_exists($this, 'folder') ? $this->folder : '';

        $files = $this->getFiles();
        
        if (array_values($files) != $files && $files != []) {
            $folder = $files;
        };

        return !is_array($folder) ? $folder : (array_key_exists($field, $folder) ? $folder[$field] : '');
    }
    
    /**
     * Get property of isOnlyFileName or get from config file
     * @return boolean
     */
    protected function isOnlyFileName()
    {
        return property_exists($this, 'onlyFileName') ? $this->onlyFileName : true;
    }

    /**
     * Get Path of File base on Field
     * @param  string $field
     * @param  File $file
     * @return string
     */
    public function getFolderPath($field)
    {
        $baseFolder = $this->getBaseFolder();
        
        $folder = $this->getFolderField($field);

        return base_path($baseFolder.'/'.$folder);
    }

    /**
     * Get Path of File base on Field
     * @param  string $field
     * @param  File $file
     * @return string
     */
    public function getFilePath($field, $file)
    {
        $folder = $this->getFolderPath($field);

        return $folder.'/'.basename($file);
    }
}
