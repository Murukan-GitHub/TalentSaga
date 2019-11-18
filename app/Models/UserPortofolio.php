<?php

namespace App\Models;

use Cache;
use Suitcore\Models\SuitModel;

/*
|--------------------------------------------------------------------------
| user_portofolios Table Structure
|--------------------------------------------------------------------------
| * id
| * user_id
| * event_date
| * event_name
| * description
| * url
| * youtube_url
| * status ('draft', 'published')
| * created_at
| * updated_at
*/
class UserPortofolio extends SuitModel
{
    // MODEL DEFINITION
    const PUBLISHED_STATUS = 'published';
    const DRAFT_STATUS = 'draft';

    public $table = 'user_portofolios';

    protected static $bufferAttributeSettings = null;

    public $fillable = [
        'user_id',
        'event_date',
        'event_name',
        'description',
        'url',
        'youtube_url',
        'status'
    ];

    public $rules = [
        'user_id' => 'required|exists:users,id',
        'event_date' => 'required',
        'event_name' => 'required',
        'description' => 'required',
        'status' => 'required'
    ];

    protected $dates = ['event_date'];

    /**
     * Scope Published
     **/
    public function scopePublished($query) {
        return $query->where('status', self::PUBLISHED_STATUS);
    }

    /**
     * Scope Draft
     **/
    public function scopeDraft($query) {
        return $query->where('status', self::DRAFT_STATUS);
    }

    /**
     * @return User|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get label name of Model
     * @return string
     */
    public function getLabel()
    {
        return 'User Portofolio';
    }

    /**
     * Get format text name
     *
     * @return string
     */
    public function getFormattedValue()
    {
        return $this->event_name;
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
     * Get options of status
     *
     */
    public function getStatusOptions() {
        return [self::DRAFT_STATUS => ucfirst(strtolower(self::DRAFT_STATUS)),
                self::PUBLISHED_STATUS => ucfirst(strtolower(self::PUBLISHED_STATUS))
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
            'event_date' => [
                "type" => self::TYPE_DATE,
                'visible' => true,
                'formdisplay' => true,
                'required' => true,
                'relation' => null,
                'label' => 'Event Date'
            ],
            'event_name' => [
                "type" => self::TYPE_TEXT,
                'visible' => true,
                'formdisplay' => true,
                'required' => true,
                'relation' => null,
                'label' => 'Event Name'
            ],
            'description' => [
                "type" => self::TYPE_TEXT,
                'visible' => false,
                'formdisplay' => true,
                'required' => true,
                'relation' => null,
                'label' => 'Description'
            ],
            'url' => [
                "type" => self::TYPE_TEXT,
                'visible' => true,
                'formdisplay' => true,
                'required' => false,
                'relation' => null,
                'label' => 'Event URL'
            ],
            'youtube_url' => [
                "type" => self::TYPE_TEXT,
                'visible' => true,
                'formdisplay' => true,
                'required' => false,
                'relation' => null,
                'label' => 'Event Youtube URL'
            ],
            "status" => [
                "type" => self::TYPE_TEXT,
                "visible" => true,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "Status",
                "options" => $this->getStatusOptions()
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
