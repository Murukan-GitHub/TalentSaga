<?php

namespace App\Models;

use Cache;
use Suitcore\Models\SuitModel;

/*
|--------------------------------------------------------------------------
| Countries Table Structure
|--------------------------------------------------------------------------
| * id INT(10) NOT NULL
| * code VARCHAR(4) NOT NULL
| * name VARCHAR(48) NOT NULL
| * created_at TIMESTAMP
| * updated_at TIMESTAMP
*/
class Country extends SuitModel
{
    // MODEL DEFINITION

    public $table = 'countries';

    protected static $bufferAttributeSettings = null;

    public $fillable = [
        'code',
        'name',
        'is_active'
    ];

    public $rules = [
        'code'  => 'required',
        'name'  => 'required',
        'is_active' => 'required'
    ];

    // SCOPES
    public function scopeActive($query) {
        return $query->where('is_active', '=', 1);
    }

    // RELATIONSHIP
    /**
     * Get provincies of the country.
     *
     * @return City|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cities()
    {
        return $this->hasMany(City::class);
    }

    /**
     * Get default name of Country
     *
     * @return string
     */
    public static function getDefault()
    {
        return "Indonesia";
    }

    /**
     * Get list of all countries
     *
     * @return array
     */
    public static function allCountries()
    {
        return Cache::remember('countries', 60, function(){
            return Country::get();
        });
    }

    /**
     * Get List all of Countries
     *
     * @return array
     */
    public static function getHeaderDropDownList()
    {
        $lists = [];
        $countries = Country::allCountries();

        $lists[0]['key_country'] = 'all';
        $lists[0]['name_country'] = 'Seluruh Negara';
        foreach ($countries as $country) {
            $lists[$country->code]['key_country'] = $country->name;
            $lists[$country->code]['name_country'] = $country->name;
        }

        return $lists;
    }

    /**
     * Get label name of Model
     * @return string
     */
    public function getLabel()
    {
        return 'Country';
    }

    /**
     * Get format text name
     *
     * @return string
     */
    public function getFormattedValue()
    {
        return $this->name;
    }

    public function getFormattedValueColumn()
    {
        return ['name'];
    }

    /**
     * Get options of model
     *
     * @return array
     */
    public function getOptions()
    {
        return self::all();
    }

    /**
     * Get Attributes of Setting
     *
     * @return array
     */
    public function getAttributeSettings()
    {
        // default attribute settings of generic model, override for furher use
        return [
            'id' => [
                'type' => self::TYPE_NUMERIC,
                'visible' => true,
                'formdisplay' => false,
                'required' => true,
                'relation' => null,
                'label' => 'ID'
            ],
            'code' => [
                'type' => self::TYPE_TEXT,
                'visible' => true,
                'formdisplay' => true,
                'required' => true,
                'relation' => null,
                'label' => 'Code'
            ],
            'name' => [
                'type' => self::TYPE_TEXT,
                'visible' => true,
                'formdisplay' => true,
                'required' => true,
                'relation' => null,
                'label' => 'Name',
                "translation" => true
            ],
            "is_active" => [
                "type" => self::TYPE_BOOLEAN,
                "visible" => true,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "Is Active?"
            ],
            'created_at' => [
                'type' => self::TYPE_DATETIME,
                'visible' => false,
                'formdisplay' => false,
                'required' => false,
                'relation' => null,
                'label' =>'Created At'
            ],
            'updated_at' => [
                'type' => self::TYPE_DATETIME,
                'visible' => false,
                'formdisplay' => false,
                'required' => false,
                'relation' => null,
                'label' => 'Updated At'
            ]
        ];
    }
}
