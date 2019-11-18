<?php

namespace App\Models;

use Cache;
use Suitcore\Models\SuitModel;

/*
|--------------------------------------------------------------------------
| user_profiles Table Structure
|--------------------------------------------------------------------------
| * id INT(10) NOT NULL
| * user_id INT(10) NOT NULL TO ‘users’
| * talent_category_id -> talent_categories
| * talent_profession
| * talent_expertise_id -> talent_expertises
| * talent_description
| * currency_id
| * price_estimation
| * pricing_metric
| * price_notes
| * contact_for_price : true, false
| * country_id --> countries
| * city_id --> cities
| * street_name
| * street_number
| * zip_code
| * gender
| * weight
| * height
| * facebook_page
| * twitter_page
| * instagram_page
| * youtube_page
| * status ('draft', 'published')
| * created_at DATETIME NOT NULL
| * updated_at DATETIME NOT NULL
*/
class UserProfile extends SuitModel
{
    // MODEL DEFINITION
    const GENDER_MALE = 'male';
    const GENDER_FEMALE = 'female';
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';

    public $table = 'user_profiles';

    protected static $bufferAttributeSettings = null;

    public $fillable = [
        'user_id',
        'talent_category_id', //
        'talent_profession', //
        'talent_expertise_id', //
        'talent_description', //
        'currency_id', ////
        'price_estimation', //
        'pricing_metric', ////
        'price_notes', //
        'contact_for_price', //
        'country_id',
        'city_id',
        'street_name', //
        'street_number', //
        'zip_code', //
        'gender',
        'weight',
        'height',
        'facebook_page',
        'twitter_page',
        'instagram_page',
        'youtube_page',
        'status'
    ];

    public $rules = [
        'user_id' => 'required|exists:users,id',
        'talent_category_id' => 'required',
        'talent_profession' => 'required',
        'talent_expertise_id' => 'required',
        'country_id' => 'required|exists:countries,id',
        // 'city_id' => 'required|exists:cities,id',
        'price_notes' => 'required',
        'contact_for_price' => 'required',
        'status' => 'required',
    ];

    protected $appends = ['available_cities_name'];

    public function getAvailableCitiesNameAttribute() {
        $listCities = [];
        if ($this->city) {
            $listCities[] = $this->city->name;
        } 
        if ($this->user && $this->user->availabilityAreas && $this->user->availabilityAreas->count() > 0) {
            foreach ($this->user->availabilityAreas as $key => $city) {
                if ($city->id != $this->city->id) {
                    $listCities[] = $city->name;
                }
            }
        }
        asort($listCities);
        return (empty($listCities) ? '-' : implode(", ", $listCities));
    }

    /**
     * @return User|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return Currency|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * @return TalentCategory|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function talentCategory()
    {
        return $this->belongsTo(TalentCategory::class);
    }

    /**
     * @return TalentExpertise|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function talentExpertise()
    {
        return $this->belongsTo(TalentExpertise::class);
    }

    /**
     * @return TalentExpertise|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function talentExpertises()
    {
        return $this->belongsToMany('App\Models\TalentExpertise', 'user_profile_expertises', 'user_profile_id', 'talent_expertise_id');
    }

    /**
     * @return UserProfileExpertise|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userProfileExpertises()
    {
        return $this->hasMany(UserProfileExpertise::class, 'user_profile_id');
    }

    /**
     * @return City|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * @return Country|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function scopeSimpleSearch($query, $searchTerm) {
        return $query->where(function ($qsearch) use($searchTerm) {
            // name and lastname
            $searchTerm = preg_replace("/[^a-zA-Z0-9\" ]+/", "\\ $0", $searchTerm);
            $searchTerm = str_replace("\\ ", "\\", $searchTerm);
            $searchTerms = implode('|', array_unique( explode(' ', preg_replace('!\s+!', ' ', trim($searchTerm))) ) );

            // profession
            $qsearch->where('talent_profession','rlike',$searchTerms);
            // expertise
            $qsearch->orWhereHas('userProfileExpertises', function($qexpertises) use($searchTerms) {
                $qexpertises->whereHas('talentExpertise', function($qexpertise) use($searchTerms) {
                    $qexpertise->where('name','rlike',$searchTerms);
                    $qexpertise->orWhereHas('category', function($qexpertisecat) use($searchTerms) {
                        $qexpertisecat->where('name','rlike',$searchTerms);
                    });
                });
            });
            // location
            $qsearch->orWhereHas('city', function($qcity) use($searchTerms) {
                $qcity->where('name','rlike',$searchTerms);
            });
            $qsearch->orWhereHas('country', function($qcountry) use($searchTerms) {
                $qcountry->where('name','rlike',$searchTerms);
            });
            // user
            $qsearch->orWhereHas('user', function($quser) use($searchTerms) {
                $quser->where('name','rlike',$searchTerms);
                $quser->orWhere('last_name','rlike',$searchTerms);
                // on availability area
                $quser->orWhereHas('userAvailabilityAreas', function($qarea) use($searchTerms) {
                    $qarea->whereHas('city', function($qcity) use($searchTerms) {
                        $qcity->where('name','rlike',$searchTerms);
                    });
                });
            });
        });
    }

    /**
     * Get list of all userprofiles
     *
     * @return array
     */
    public static function allUserProfiles()
    {
        return Cache::remember('user_profiles', 60, function(){
            return UserProfile::get();
        });
    }

    /**
     * Get label name of Model
     * @return string
     */
    public function getLabel()
    {
        return 'User Profile';
    }

    /**
     * Get format text name
     *
     * @return string
     */
    public function getFormattedValue()
    {
        return $this->user_id;
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

    public function getGenderOptions() {
        return [
            self::GENDER_MALE => ucfirst(strtolower(self::GENDER_MALE)),
            self::GENDER_FEMALE => ucfirst(strtolower(self::GENDER_FEMALE))
        ];
    }

    public function getStatusOptions() {
        return [
            self::STATUS_DRAFT => ucfirst(strtolower(self::STATUS_DRAFT)),
            self::STATUS_PUBLISHED => ucfirst(strtolower(self::STATUS_PUBLISHED))
        ];
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
            'user_id' => [
                'type' => self::TYPE_NUMERIC,
                'visible' => true,
                'formdisplay' => true,
                'required' => true,
                'relation' => 'user',
                'label' => 'User',
                "options" => [], // always initiated
                "readonly" => true,
                "initiated" => true
            ],
            'talent_category_id' => [
                'type' => self::TYPE_NUMERIC,
                'visible' => true,
                'formdisplay' => true,
                'required' => true,
                'relation' => 'talentCategory',
                'label' => 'Talent Category',
                "options" => (new TalentCategory)->all()->pluck('name','id')
            ],
            'talent_profession' => [
                'type' => self::TYPE_TEXT,
                'visible' => true,
                'formdisplay' => true,
                'required' => true,
                'relation' => null,
                'label' => 'Profession'
            ],
            'talent_expertise_id' => [
                'type' => self::TYPE_NUMERIC,
                'visible' => true,
                'formdisplay' => true,
                'required' => true,
                'relation' => 'talentExpertise',
                'label' => 'Talent Expertise',
                "options" => (new TalentExpertise)->all()->pluck('name','id')
            ],
            'talent_description' => [
                'type' => self::TYPE_TEXTAREA,
                'visible' => false,
                'formdisplay' => true,
                'required' => false,
                'relation' => null,
                'label' => 'Talent Description'
            ],
            'price_estimation' => [
                'type' => self::TYPE_NUMERIC,
                'visible' => true,
                'formdisplay' => true,
                'required' => false,
                "relation" => null,
                'label' => 'Price Estimation'
            ],
            'price_notes' => [
                'type' => self::TYPE_TEXTAREA,
                'visible' => true,
                'formdisplay' => true,
                'required' => true,
                'relation' => null,
                'label' => 'Price Notes'
            ],
            'contact_for_price' => [
                'type' => self::TYPE_BOOLEAN,
                'visible' => true,
                'formdisplay' => true,
                'required' => true,
                'relation' => null,
                'label' => 'Contact for Price?'
            ],
            'country_id' => [
                'type' => self::TYPE_NUMERIC,
                'visible' => true,
                'formdisplay' => true,
                'required' => true,
                'relation' => 'country',
                'label' => 'Country',
                "options" => (new Country)->all()->pluck('name','id')
            ],
            'city_id' => [
                'type' => self::TYPE_NUMERIC,
                'visible' => true,
                'formdisplay' => true,
                'required' => false,
                'relation' => 'city',
                'label' => 'City',
                "options" => [],
            ],
            'street_name' => [
                'type' => self::TYPE_TEXT,
                'visible' => false,
                'formdisplay' => true,
                'required' => false,
                'relation' => null,
                'label' => 'Street Name'
            ],
            'street_number' => [
                'type' => self::TYPE_NUMERIC,
                'visible' => false,
                'formdisplay' => true,
                'required' => false,
                'relation' => null,
                'label' => 'Street Number'
            ],
            'zip_code' => [
                'type' => self::TYPE_TEXT,
                'visible' => false,
                'formdisplay' => true,
                'required' => false,
                'relation' => null,
                'label' => 'Postal Code'
            ],
            'gender' => [
                'type' => self::TYPE_TEXT,
                'visible' => true,
                'formdisplay' => true,
                'required' => false,
                'relation' => null,
                'label' => 'Gender',
                'options' => $this->getGenderOptions()
            ],
            'weight' => [
                'type' => self::TYPE_FLOAT,
                'visible' => true,
                'formdisplay' => true,
                'required' => false,
                "relation" => null,
                'label' => 'Weight'
            ],
            'height' => [
                'type' => self::TYPE_FLOAT,
                'visible' => true,
                'formdisplay' => true,
                'required' => false,
                "relation" => null,
                'label' => 'Height'
            ],
            'facebook_page' => [
                'type' => self::TYPE_TEXT,
                'visible' => false,
                'formdisplay' => true,
                'required' => false,
                'relation' => null,
                'label' => 'Facebook Page'
            ],
            'twitter_page' => [
                'type' => self::TYPE_TEXT,
                'visible' => false,
                'formdisplay' => true,
                'required' => false,
                'relation' => null,
                'label' => 'Twitter Page'
            ],
            'instagram_page' => [
                'type' => self::TYPE_TEXT,
                'visible' => false,
                'formdisplay' => true,
                'required' => false,
                'relation' => null,
                'label' => 'Instagram Page'
            ],
            'youtube_page' => [
                'type' => self::TYPE_TEXT,
                'visible' => false,
                'formdisplay' => true,
                'required' => false,
                'relation' => null,
                'label' => 'Youtube Page'
            ],
            'status' => [
                'type' => self::TYPE_TEXT,
                'visible' => true,
                'formdisplay' => true,
                'required' => true,
                'relation' => null,
                'label' => 'Status',
                'options' => $this->getStatusOptions()
            ],
            'created_at' => [
                'type' => self::TYPE_DATETIME,
                'visible' => true,
                'formdisplay' => false,
                'required' => false,
                'relation' => null,
                'label' =>'Created At'
            ],
            'updated_at' => [
                'type' => self::TYPE_DATETIME,
                'visible' => true,
                'formdisplay' => false,
                'required' => false,
                'relation' => null,
                'label' => 'Updated At'
            ]
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {

        });

        static::saved(function ($model) {
            Cache::forget('user_completion_' . $model->user_id);
        });

        static::deleted(function ($model) {
            Cache::forget('user_completion_' . $model->user_id);
        });
    }
}
