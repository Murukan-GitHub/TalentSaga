<?php

namespace App\Models;

use Cache;
use Suitcore\Models\SuitModel;

/*
|--------------------------------------------------------------------------
| user_stories Table Structure
|--------------------------------------------------------------------------
| * id
| * user_id
| * title
| * highlight
| * cover_image
| * content
| * status ('draft', 'published')
| * created_at
| * updated_at
*/
class UserStory extends SuitModel
{
    // MODEL DEFINITION
    const PUBLISHED_STATUS = 'published';
    const DRAFT_STATUS = 'draft';

    public $table = 'user_stories';

    protected static $bufferAttributeSettings = null;

    public $fillable = [
        'user_id',
        'title',
        'highlight',
        'cover_image',
        'content',
        'status'
    ];

    public $rules = [
        'user_id' => 'required|exists:users,id',
        'title' => 'required',
        // 'status' => 'required',
        'content' => 'required',
    ];

    public $imageAttributes = [
        'cover_image' => 'userstorycovers'
    ];

    public $files = [
        'cover_image' => 'userstorycovers'
    ];

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
        return 'User Story';
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
                "options" => [],
                //"readonly" => true,
                //"initiated" => true
            ],
            "title" => [
                "type" => self::TYPE_TEXT,
                "visible" => true,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "Title"
            ],
            "highlight" => [
                "type" => self::TYPE_TEXTAREA,
                "visible" => false,
                "formdisplay" => true,
                "required" => false,
                "relation" => null,
                "label" => "Highlight"
            ],
            "cover_image" => [
                "type" => self::TYPE_FILE,
                "visible" => false,
                "formdisplay" => true,
                "required" => false,
                "relation" => null,
                "label" => "Cover Images"
            ],
            "content" => [
                "type" => self::TYPE_RICHTEXTAREA,
                "visible" => false,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "Contents"
            ],
            "status" => [
                "type" => self::TYPE_TEXT,
                "visible" => true,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "Status",
                "options" => $this->getStatusOptions(),
                "filterable" => true
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

        });
    }
}
