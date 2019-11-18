<?php

namespace App\Models;

use Cache;
use Suitcore\Models\SuitModel;

/*
|--------------------------------------------------------------------------
| faqs Table Structure
|--------------------------------------------------------------------------
| * id INT(10) NOT NULL
| * faq_category_id INT(10) NULL
| * position_order NULL
| * question VARCHAR(1024) NOT NULL
| * answer TEXT NOT NULL
| * created_at TIMESTAMP
| * updated_at TIMESTAMP
*/
class Faq extends SuitModel
{
    // MODEL DEFINITION
    public $table = 'faqs';
    protected static $bufferAttributeSettings = null;

    public $fillable = [
        'faq_category_id',
        'position_order',
        'question',
        'answer'
    ];

    public $rules = [
        'question' => 'required',
        'answer' => 'required'
    ];

    public function faqCategory()
    {
        return $this->belongsTo(FaqCategory::class, 'faq_category_id');
    }
    
    public function getLabel()
    {
        return "F.A.Q";
    }

    public function getFormattedValue()
    {
        return "#".$this->id;
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
            "faq_category_id" => [
                "type" => self::TYPE_NUMERIC,
                "visible" => false,
                "formdisplay" => false,
                "required" => false,
                "relation" => "faqCategory",
                "label" => "FAQ Category",
                "options" => [] // (new FaqCategory)->all()->pluck('name','id'),
                // "filterable" => true
            ],
            "position_order" => [
                "type" => self::TYPE_NUMERIC,
                "visible" => true,
                "formdisplay" => true,
                "required" => false,
                "relation" => null,
                "label" => "Position Order"
            ],
            "question" => [
                "type" => self::TYPE_TEXT,
                "visible" => true,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "Question",
                "translation" => true
            ],
            "answer" => [
                "type" => self::TYPE_RICHTEXTAREA,
                "visible" => false,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "Answer",
                "translation" => true
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
            Cache::forget('faq_by_category_all');
            Cache::forget('faq_by_category_'.$model->faq_category_id);
        });

        static::deleted(function ($model) {
            Cache::forget('faq_by_category_all');
            Cache::forget('faq_by_category_'.$model->faq_category_id);
        });
    }
}
