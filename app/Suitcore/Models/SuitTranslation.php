<?php

namespace Suitcore\Models;

use Cache;
use DB;
use Suitcore\Models\SuitModel;

/*
|--------------------------------------------------------------------------
| translations Table Structure
|--------------------------------------------------------------------------
| * id INT(10) NOT NULL
| * class VARCHAR(64)
| * identifier VARCHAR(64)
| * attribute VARCHAR(64)
| * locale VARCHAR(2)
| * value MEDIUMTEXT
| * created_at TIMESTAMP
| * updated_at TIMESTAMP
*/
class SuitTranslation extends SuitModel
{
    // MODEL DEFINITION
    public $table = 'translations';
    protected static $bufferAttributeSettings = null;

    public $fillable = [
        'id',
        'class',
        'identifier',
        'attribute', 
        'locale', 
        'value'
    ];
    
    public function rules()
    {
        return [
            'class' => 'required',
            'identifier' => 'required',
            'attribute' => 'required',
            'locale' => 'required',
            'value' => 'required',
        ];
    }

    public function scopeInstance($query, $class, $id)
    {
        return $query->where('class', '=', strtolower($class))
                     ->where('identifier', '=', "".$id);
    }
     
    public function scopeField($query, $attribute)
    {
        return $query->where('attribute', '=', $attribute);
    }
     
    public function scopeLocale($query, $locale)
    {
        return $query->where('locale', '=', $locale);
    }

    public function getLabel() {
        return "Translations";
    }

    public function getFormattedValue() {
        return $this->value;
    }

    public function getAttributeSettings() {
        return [
            "id" => [
                "type" => self::TYPE_NUMERIC,
                "visible" => true,
                "formdisplay" => false,
                "required" => true,
                "relation" => null,
                "label" => "ID"
            ],
            "class" => [
                "type" => self::TYPE_TEXT,
                "visible" => true,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "Class / Model"
            ],
            "identifier" => [
                "type" => self::TYPE_TEXT,
                "visible" => true,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "Identifier"
            ],
            "attribute" => [
                "type" => self::TYPE_TEXT,
                "visible" => true,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "Attribute"
            ],
            "locale" => [
                "type" => self::TYPE_TEXT,
                "visible" => true,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "Locale"
            ],
            "value" => [
                "type" => self::TYPE_TEXT,
                "visible" => false,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "Value"
            ],
            "created_at" => [
                "type" => self::TYPE_DATETIME,
                "visible" => false,
                "formdisplay" => false,
                "required" => true,
                "relation" => null,
                "label" => "Created At"
            ],
            "updated_at" => [
                "type" => self::TYPE_DATETIME,
                "visible" => true,
                "formdisplay" => false,
                "required" => true,
                "relation" => null,
                "label" => "Updated At"
            ]
        ];
    }

    public static function trans($locale, $class, $identifier, $attribute) {
        $object = Cache::rememberForever('translation_'.$locale."_".$class."_".$identifier, function () use($locale, $class, $identifier) {
            $translation = SuitTranslation::select('*')->instance($class, $identifier)->locale($locale)->get();
            if ($translation && count($translation) > 0) return $translation->keyBy('attribute');
            return ['empty' => null];
        });
        return ($object && isset($object[$attribute]) && $object[$attribute] ? $object[$attribute]->value : '');
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($model) {
            Cache::forget('translation_'.$model->locale."_".$model->class."_".$model->identifier);
        });

        static::deleted(function ($model) {
            Cache::forget('translation_'.$model->locale."_".$model->class."_".$model->identifier);
        });
    }
}
