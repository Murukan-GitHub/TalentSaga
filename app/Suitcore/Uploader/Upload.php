<?php

namespace Suitcore\Uploader;

use Suitcore\File\DummyFile;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Suitcore\Uploader\Contracts\ModelUploaderInterface as Model;

class Upload
{
    protected static $filenameMaker;

    protected $request;

    protected $file;

    protected $uploaded = [];

    protected $failed = [];

    protected $override = false;

    protected $modelOverride = true;

    protected $baseFolder = 'public/files';

    protected $folder = '';

    public function __construct($config = [])
    {
        $this->request = request();

        $this->with(config('suitapp.uploader'));

        $this->with($config);
    }

    /**
     * Set config
     * @param  array  $config
     * @return $this
     */
    public function with($config = [])
    {
        $defaults = collect(get_object_vars($this))->except('request', 'file', 'uploaded', 'failed')->toArray();

        $configs = array_replace($defaults, $config);

        foreach ($configs as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }

        return $this;
    }

    /**
     * Get Folder Property
     * @return string
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * Get Base Folder Property
     * @return string
     */
    public function getBaseFolder()
    {
        return $this->baseFolder;
    }

    /**
     * Upload File/s
     * @param  string $field
     * @return DummyFile|File when Failure or Success
     */
    public function file($field = null)
    {
        $file = $this->getUploadedFileRequest($field);

        //  if not valid or not secure
        if ($file == null || !$this->isValidAndSecure($file)) {
        
            $this->failed = $file;
        
            $this->uploaded = null;
        
            @unlink($file);
        
            return new DummyFile($this);
        }

        $folder = base_path($this->baseFolder).'/'.$this->folder;
        
        $filename = $this->processUpload($file);
        
        //  upload to defined folder
        $this->uploaded = $file->move($folder, $filename);

        return new File($this);
    }

    public function setFilenameMaker()
    {
        static::$filenameMaker = func_get_args();
    }

    public function makeFileName($file)
    {
        if (static::$filenameMaker != null) {
            $args = static::$filenameMaker;
            $func = array_shift($args);
            // dd(array_merge([$file], $args));
            return call_user_func_array($func, array_merge([$file], $args));
        }

        return md5($file->getClientOriginalName()).'.'.$file->getClientOriginalExtension();
    }

    /**
     * Process Uploading
     * @param  File $file
     * @return string   Filename after uppload
     */
    protected function processUpload($file)
    {
        $desireName = $this->makeFileName($file);
        $filename = pathinfo($desireName, PATHINFO_FILENAME); // file
        $extension = pathinfo($desireName, PATHINFO_EXTENSION); // jpg

        $folder = base_path($this->baseFolder).'/'.$this->folder;

        if (file_exists($oldFile = $folder.'/'.$filename.'.'.$extension)) {

            if ($this->override) {
            
                @unlink($oldFile);
            
            } else {
            
                $currentName = $filename;
            
                do {
                    $filename = $currentName. '.'.$this->generateRandomString();
                } while (file_exists($folder.'/'.$filename.'.'.$extension));
            }
        };

        return $filename.'.'.$extension;
    }

    /**
     * Get File
     * @return File clearly
     */
    public function getFile()
    {
        return $this->uploaded ? $this->uploaded : null;
    }

    /**
     * Upload Files
     * @param  string $fileField
     * @return boolean|array
     */
    public function files($fileFields = [], callable $callback = null)
    {
        $fields = (array) $fileFields;

        $this->uploaded = [];

        foreach ($fields as $field) {

            if (($file = (new static)->file($field, $callback)) === false) {
                $this->failed[$field] = $file;
                continue;
            }
            
            $this->uploaded[$field] = is_callable($callback) ? $callback($file, $field) : $file;

        }

        // if (is_callable($callback)) {
        //     $this->uploaded = array_map($callback, $files = $this->uploaded, array_keys($files));
        // }

        return new Collection($this->uploaded);
    }

    /**
     * Get Files that has been upload
     * @return file|array of files
     */
    public function getUploaded()
    {
        return $this->uploaded;
    }

    /**
     * Get Files that has been failed
     * @return file|array of files
     */
    public function getFailed()
    {
        return $this->failed;
    }

    /**
     * Get Uploaded Files Request
     * @param  string|array $fields
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    protected function getUploadedFileRequest($field = null)
    {
        if (!$this->request->hasFile($field)) {
            return false;
        }

        return $this->request->file($field);
    }

    /**
     * Generate random string
     * @return string
     */
    private function generateRandomString($len = 5)
    {
        return substr(str_shuffle(str_repeat('abcdefghijklmnopqrstuvwxyz0123456789', $len)), 0, $len);
    }

    protected function generateFileName($file)
    {
        $filename = pathinfo($file, PATHINFO_FILENAME); // file
        $extension = pathinfo($file, PATHINFO_EXTENSION); // jpg
        return $filename.'.'.$this->generateRandomString().'.'.$extension;
    }

    /**
     * [isValid check that file is valid and secure
     * @param  File  $file
     * @return boolean true if valid and secure
     */
    protected function isValidAndSecure(UploadedFile $file)
    {
        if (! $file->isValid() /*|| $file->isExecutable()*/ || $file->getClientOriginalExtension() == 'php') {
            return false;
        }

        $finfo = finfo_open(FILEINFO_MIME);

        //check to see if the mime-type contains '/x-' means executable file
        if (strpos(finfo_file($finfo, $file), '/x-')) {
            return false;
        }

        return true;
    }

    /**
     * Upload files based on model
     * @param  Model   $model           model instance
     * @param  boolean $directSaveModel if true, dirrectly do save model to database
     * @return model
     */
    public function model(Model $model, $directSaveModel = true)
    {
        $this->files($model->getFields(), function ($file, $field) use ($model, $directSaveModel) {
            if (!$this->request->hasFile($field)) {
                return;
            }

            $oldFile = $model->getFile($field);

            if ($oldFile != null && $this->modelOverride && $directSaveModel) {
                $model->deleteFile($field, $oldFile);
            }

            $file = $file->move($model->getFolderPath($field));

            $model->setFile($field, $file);
        });

        if ($directSaveModel) {
            $model->save();
        }
        
        return $model;
    }

    /**
     * Delete Uploaded files based on model
     * @param  Model   $model           model instance
     * @param  string[] $attributeKeys  Array of key attributes
     * @return model
     */
    public function cleanUploaded(Model $model, $attributeKeys, $directSaveModel = true)
    {
        foreach ($model->getFields() as $field) {
            if (in_array($field, $attributeKeys)) {
                $oldFile = $model->getFile($field);
                if ($oldFile != null && $this->modelOverride) {
                    $model->deleteFile($field, $oldFile);
                }
                $model->setFile($field, '');
            }
        }
        if ($directSaveModel) {
            $model->save();
        }
        // return object
        return $model;
    }
}
