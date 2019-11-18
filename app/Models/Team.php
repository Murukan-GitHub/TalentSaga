<?php

namespace App\Models;

use Cache;
use DB;
use Suitcore\Models\SuitModel;

/*
|--------------------------------------------------------------------------
| teams Table Structure
|--------------------------------------------------------------------------
| * id INT(10) NOT NULL
| * position_order INT(10) NOT NULL
| * name varchar(64) NOT NULL
| * position varchar(512)
| * about TEXT NOT NULL
| * avatar TEXT NULL
| * created_at TIMESTAMP
| * updated_at TIMESTAMP
*/
class Team extends SuitModel
{
    // MODEL DEFINITION
    public $table = 'teams';

    protected static $bufferAttributeSettings = null;

    public $imageAttributes = [
        'avatar' => 'teamavatars'
    ];

    public $files = [
        'avatar' => 'teamavatars'
    ];


    public $fillable = [
        'position_order',
        'name',
        'position',
        'about',
        'avatar'
    ];

    public $rules = [
        'name' => 'required',
        'position' => 'required',
    ];

    public function getLabel() {
        return "Team";
    }

    public function getFormattedValue() {
        return $this->title;
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
                "label" => "Name"
            ],
            "position" => [
                "type" => self::TYPE_TEXTAREA,
                "visible" => true,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "Position / Job Level",
                "translation" => true
            ],
            "about" => [
                "type" => self::TYPE_RICHTEXTAREA,
                "visible" => false,
                "formdisplay" => true,
                "required" => false,
                "relation" => null,
                "label" => "About",
                "translation" => true
            ],
            "avatar" => [
                "type" => self::TYPE_FILE,
                "visible" => false,
                "formdisplay" => true,
                "required" => false,
                "relation" => null,
                "label" => "Avatar Images"
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
                "visible" => false,
                "formdisplay" => false,
                "required" => true,
                "relation" => null,
                "label" => "Updated At"
            ]
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            Cache::forget('talentsaga_team_member');
        });

        static::updating(function ($model) {
            Cache::forget('talentsaga_team_member');
        });
    }
}
