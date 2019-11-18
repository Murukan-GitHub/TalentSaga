<?php

namespace App\Models;

use Cache;
use Suitcore\Models\SuitModel;

/*
|--------------------------------------------------------------------------
| user_galleries Table Structure
|--------------------------------------------------------------------------
| * id
| * user_id
| * type ('image','video')
| * position_order
| * title NULLABLE
| * cover_url
| * image_media_url
| * external_media_url
| * status ('draft', 'published')
| * created_at
| * updated_at
*/
class UserGallery extends SuitModel
{
    // MODEL DEFINITION
    const TYPE_IMAGE = 'image';
    const TYPE_VIDEO = 'video';
    const PUBLISHED_STATUS = 'published';
    const DRAFT_STATUS = 'draft';

    public $table = 'user_galleries';

    protected static $bufferAttributeSettings = null;

    public $fillable = [
        'user_id',
        'type',
        'position_order',
        'title',
        'cover_url',
        'image_media_url',
        'external_media_url',
        'status' 
    ];

    public $rules = [
        'user_id' => 'required|exists:users,id',
        'position_order' => 'integer',
        'type' => 'required',
        'title' => 'required',
        'status' => 'required'
    ];

    public $imageAttributes = [
        'cover_url' => 'usergallerycovers',
        'image_media_url' => 'usergalleryimages'
    ];

    public $files = [
        'cover_url' => 'usergallerycovers',
        'image_media_url' => 'usergalleryimages'
    ];

    protected $appends = [ 'youtube_code' ];

    public function getYoutubeCodeAttribute() {
        if (!empty($this->external_media_url)) {
            $getParams = [];
            parse_str( parse_url( $this->external_media_url, PHP_URL_QUERY ), $getParams );
            return (isset($getParams['v']) ? $getParams['v'] : ''); 
        }
        return "";
    }

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
        return 'User Galleries';
    }

    /**
     * Get format text name
     *
     * @return string
     */
    public function getFormattedValue()
    {
        return $this->title;
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
     * Get options of type
     *
     */
    public function getTypeOptions() {
        return [self::TYPE_IMAGE => ucfirst(strtolower(self::TYPE_IMAGE)),
                self::TYPE_VIDEO => ucfirst(strtolower(self::TYPE_VIDEO))
        ];
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
            "type" => [
                "type" => self::TYPE_TEXT,
                "visible" => true,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "Type",
                "options" => $this->getTypeOptions()
            ],
            "position_order" => [
                "type" => self::TYPE_NUMERIC,
                "visible" => true,
                "formdisplay" => true,
                "required" => false,
                "relation" => null,
                "label" => "Position Order"
            ],
            'title' => [
                "type" => self::TYPE_TEXT,
                "visible" => true,
                'formdisplay' => true,
                'required' => true,
                'relation' => null,
                'label' => 'Title'
            ],
            'cover_url' => [
                "type" => self::TYPE_FILE,
                "visible" => false,
                'formdisplay' => true,
                'required' => false,
                'relation' => null,
                'label' => 'Cover Images'
            ],
            'image_media_url' => [
                "type" => self::TYPE_FILE,
                "visible" => true,
                'formdisplay' => true,
                'required' => true,
                'relation' => null,
                'label' => 'Media Images'
            ],
            'external_media_url' => [
                "type" => self::TYPE_TEXT,
                "visible" => false,
                'formdisplay' => true,
                'required' => false,
                'relation' => null,
                'label' => 'External Media URL'
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
