<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\SuitCommerce\Config\DefaultConfig;
use Suitcore\Models\SuitModel;
use DB;
use File;

class NewsletterSubscriber extends SuitModel 
{
    public $table = 'newsletter_subscribers';

    protected static $bufferAttributeSettings = null;

    public $fillable = [
        'name',
        'email'
    ];

    public $rules = [
        'email' => 'required'
    ];

    public function getLabel() {
        return "Newsletter Subscriber";
    }

    public function getFormattedValue() {
        return $this->name . " (" . $this->email . ")";
    }

    public function getOptions() {
        return self::all();
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
            "name" => [
                "type" => self::TYPE_TEXT,
                "visible" => true,
                "formdisplay" => true,
                "required" => false,
                "relation" => null,
                "label" => "Name"
            ],
            "email" => [
                "type" => self::TYPE_TEXT,
                "visible" => true,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "Email Address"
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
                "label" => "Updated At",
                "filterable" => true
            ]
       	];
   	}
}
