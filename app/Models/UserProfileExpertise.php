<?php

namespace App\Models;

use Cache;
use Suitcore\Models\SuitModel;

/*
|--------------------------------------------------------------------------
| user_profile_expertises Table Structure
|--------------------------------------------------------------------------
| * id INT(10) NOT NULL
| * user_profile_id INT(10) NOT NULL TO ‘users’
| * talent_expertise_id NOT NULL TO 'talent_expertises'
| * created_at DATETIME NOT NULL
| * updated_at DATETIME NOT NULL
*/
class UserProfileExpertise extends SuitModel
{
    // MODEL DEFINITION
    public $table = 'user_profile_expertises';

    protected static $bufferAttributeSettings = null;

    public $fillable = [
        'user_profile_id',
        'talent_expertise_id'
    ];

    public $rules = [
        'user_profile_id' => 'required|exists:users,id',
        'talent_expertise_id' => 'required|exists:talent_expertises,id'
    ];

    /**
     * @return UserProfile|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userProfile()
    {
        return $this->belongsTo(UserProfile::class);
    }

    /**
     * @return TalentExpertise|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function talentExpertise()
    {
        return $this->belongsTo(TalentExpertise::class);
    }

    /**
     * Get label name of Model
     * @return string
     */
    public function getLabel()
    {
        return 'User Talent Expertise';
    }

    /**
     * Get format text name
     *
     * @return string
     */
    public function getFormattedValue()
    {
        return $this->user_profile_id;
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
            'user_profile_id' => [
                'type' => self::TYPE_NUMERIC,
                'visible' => true,
                'formdisplay' => true,
                'required' => true,
                'relation' => 'userProfile',
                'label' => 'User Profile',
                "options" => [], // always initiated
                "readonly" => true,
                "initiated" => true
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
            if ($model->userProfile) {
                Cache::forget('user_completion_' . $model->userProfile->user_id);
            }
        });

        static::deleted(function ($model) {
            if ($model->userProfile) {
                Cache::forget('user_completion_' . $model->userProfile->user_id);
            }
        });
    }
}
