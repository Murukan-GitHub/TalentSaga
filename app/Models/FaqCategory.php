<?php

namespace App\Models;

use Cache;
use Cviebrock\EloquentSluggable\Sluggable;
use Suitcore\Models\SuitModel;

/*
|--------------------------------------------------------------------------
| faq_categories Table Structure
|--------------------------------------------------------------------------
| * id INT(10) NOT NULL
| * position_order INT(10) NULL
| * name varchar(255) NOT NULL
| * slug varchar(255)
| * created_at TIMESTAMP
| * updated_at TIMESTAMP
*/
class FaqCategory extends SuitModel
{
    use Sluggable;

    // MODEL DEFINITION
    public $table = 'faq_categories';
    protected static $bufferAttributeSettings = null;

    public $fillable = [
        'position_order',
        'name',
        'slug'
    ];

    public $rules = [
        'name' => 'required'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    /**
     * Get faqs of the category
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function faqs()
    {
        return $this->hasMany(Faq::class, 'faq_category_id');
    }

    public function getLabel()
    {
        return "FAQ Category";
    }

    public function getFormattedValue()
    {
        return $this->name;
    }

    public function getOptions()
    {
        return self::all();
    }

    public function getAttributeSettings()
    {
        return [
            "id" => [
                "type" => self::TYPE_NUMERIC,
                "visible" => true,
                "formdisplay" => false,
                "required" => true,
                "relation" => null,
                "label" => "ID"
            ],
            "position_order" => [
                "type" => self::TYPE_NUMERIC,
                "visible" => true,
                "formdisplay" => true,
                "required" => false,
                "relation" => null,
                "label" => "Position Order"
            ],
            "name" => [
                "type" => self::TYPE_TEXT,
                "visible" => true,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "Name",
                "translation" => true
            ],
            "slug" => [
                "type" => self::TYPE_TEXT,
                "visible" => false,
                "formdisplay" => true,
                "required" => false,
                "relation" => null,
                "label" => "Slug"
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

    public static function boot()
    {
        parent::boot();

        static::saved(function ($model) {
            Cache::forever('faq_categories', FaqCategory::orderBy('position_order','asc')->get()->keyBy('slug'));
        });

        static::deleted(function ($model) {
            Cache::forever('faq_categories', FaqCategory::orderBy('position_order','asc')->get()->keyBy('slug'));
        });
    }
}
