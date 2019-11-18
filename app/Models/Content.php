<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use DB;
use File;
use Suitcore\Models\SuitModel;

/*
|--------------------------------------------------------------------------
| contents Table Structure
|--------------------------------------------------------------------------
| * id INT(10) NOT NULL
| * type_id INT(10) NOT NULL
| * category_id INT(10) NOT NULL
| * title varchar(64) NOT NULL
| * slug varchar(100)
| * highlight varchar(512)
| * content TEXT NOT NULL
| * image TEXT NULL
| * attachment_file TEXT NULL
| * status varchar(50)
| * created_at TIMESTAMP
| * updated_at TIMESTAMP
*/
class Content extends SuitModel
{
    use Sluggable;

    const PUBLISHED_STATUS = 'published';
    const DRAFT_STATUS = 'draft';

    // MODEL DEFINITION
    public $table = 'contents';

    protected static $bufferAttributeSettings = null;

    public $imageAttributes = [
        'image' => 'contentimages'
    ];

    public $files = [
        'image' => 'contentimages',
        'attachment_file' => 'contentattachment'
    ];


    public $fillable = [
        'type_id',
        'category_id',
        'title',
        'slug',
        'highlight',
        'content',
        'image',
        'attachment_file',
        'status'
    ];

    public $rules = [
        'title' => 'required',
        // 'slug' => 'required',
        'content' => 'required',
        // 'status' => 'required'
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
                'source' => 'title'
            ]
        ];
    }

    public function type()
    {
        return $this->belongsTo(ContentType::class, 'type_id');
    }

    public function category()
    {
        return $this->belongsTo(ContentCategory::class, 'category_id');
    }

    public function isDynamic()
    {
        return $this->type_id == ContentType::getDynamicId();
    }

    // LOCAL SCOPEFunctionName

    public function scopeDynamicContent($query)
    {
        return $query->whereHas('type', function ($query) {
            $query->dynamicType();
        });
    }

    public function scopeStaticContent($query)
    {
        return $query->whereHas('type', function ($query) {
            $query->staticType();
        });
    }

    protected function getCategory($query, $category)
    {
        return $query->whereHas('category', function ($query) use ($category) {
            $query->where('slug', $category);
        });
    }

    /**
     * Scope a query to only include blog category.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNews($query)
    {
        return $this->getCategory($query, 'blog');
    }
    /**
     * Scope a query to only include promo category.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePromotions($query)
    {
        return $this->getCategory($query, 'promotion');
    }

    /**
     * Scope a query to only include career category.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCareers($query)
    {
        return $this->getCategory($query, 'career');
    }

    /**
     * Scope a query to only include terms category.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTerms($query)
    {
        return $this->getCategory($query, 'terms');
    }

    /**
     * Scope a query to only include buyerinfo category.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBuyerInfo($query)
    {
        return $this->getCategory($query, 'buyer-info');
    }

    /**
     * Scope a query to only include sellerinfo category.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSellerInfo($query)
    {
        return $this->getCategory($query, 'seller-info');
    }



    public function scopePublished($query)
    {
        return $query->where('status', static::PUBLISHED_STATUS);
    }

    /**
     * Get the list of dynamic content.
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getDynamicContent()
    {
        return static::dynamicContent()
                ->orderBy('category_id', 'asc')
                ->orderBy('id', 'asc')
                ->get();

    }

    /**
     * Get the list of static content.
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getStaticContent()
    {
        return static::staticContent()
                ->orderBy('category_id', 'asc')
                ->orderBy('id', 'asc')
                ->get();

    }

     /**
     * Get the first specified number of word from the content.
     * @param int $wordLimit
     * @return string
     */
    public function getShortDescription($wordLimit)
    {
        return $this->highlight ?: str_limit($this->content, $wordLimit);
    }

    /**
     * Get the specific static page.
     * @param  string $slug
     * @return \Illuminate\Database\Eloquent\Model|static|null
     */
    public static function getStaticPage($slug)
    {
        $static = static::published()
                         ->staticContent()
                         ->where('slug', '=', $slug)
                         ->first();
        return $static;
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

    public function getValidImagePath($size = 'small_square')
    {
        $image = 'image_'.$size;
        return $this->$image ?: null;
    }

    public function getLabel() {
        return "Content";
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
            "type_id" => [
                "type" => self::TYPE_NUMERIC,
                "visible" => true,
                "formdisplay" => true,
                "required" => true,
                "relation" => "type",
                "label" => "Type",
                "options" => (new ContentType)->all()->pluck('name','id'),
                "filterable" => true
            ],
            "category_id" => [
                "type" => self::TYPE_NUMERIC,
                "visible" => true,
                "formdisplay" => true,
                "required" => false,
                "relation" => "category",
                "label" => "Category",
                "options" => (new ContentCategory)->all()->pluck('name','id'),
                "filterable" => true
            ],
            "title" => [
                "type" => self::TYPE_TEXT,
                "visible" => true,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "Title",
                "translation" => true
            ],
            "slug" => [
                "type" => self::TYPE_TEXT,
                "visible" => false,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "Slug"
            ],
            "highlight" => [
                "type" => self::TYPE_TEXTAREA,
                "visible" => false,
                "formdisplay" => true,
                "required" => false,
                "relation" => null,
                "label" => "Highlight",
                "translation" => true
            ],
            "content" => [
                "type" => self::TYPE_RICHTEXTAREA,
                "visible" => false,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "Contents",
                "translation" => true
            ],
            "image" => [
                "type" => self::TYPE_FILE,
                "visible" => false,
                "formdisplay" => true,
                "required" => false,
                "relation" => null,
                "label" => "Cover Images"
            ],
            "attachment_file" => [
                "type" => self::TYPE_FILE,
                "visible" => false,
                "formdisplay" => true,
                "required" => false,
                "relation" => null,
                "label" => "Attachment File"
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
            //if (empty($model->slug)) {
            //    $model->slug = str_slug($model->title);
            //}
        });

        static::updating(function ($model) {
            //if (empty($model->slug)) {
            //    $model->slug = str_slug($model->title);
            //}
        });
    }
}
