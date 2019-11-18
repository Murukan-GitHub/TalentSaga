<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Cache;
use File;
use DB;
use Suitcore\Models\SuitModel;

/*
|--------------------------------------------------------------------------
| talent_categories Table Structure
|--------------------------------------------------------------------------
| * id INT(10) NOT NULL
| * parent_id INT(10) UNSIGNED
| * position_order INT(10) NULL
| * slug VARCHAR(45)
| * name VARCHAR(45)
| * description TEXT
| * cover_image VARCHAR(255)
| * banner_image VARCHAR(255)
| * created_at TIMESTAMP
| * updated_at TIMESTAMP
*/
class TalentCategory extends SuitModel
{
    use Sluggable;

    // MODEL DEFINITION
    public $table = 'talent_categories';
    protected static $bufferAttributeSettings = null;

    public $fillable = [
        'parent_id',
        'position_order',
        'slug',
        'name',
        'description',
        'cover_image',
        'banner_image',
        'is_active'
    ];

    public $rules = [
        'name' => 'required',
        'is_active' => 'required'
    ];

    protected $imageAttributes = [
        'cover_image' => 'categories',
        'banner_image' => 'categorybanners'
    ];

    protected $files = [
        'cover_image' => 'categories',
        'banner_image' => 'categorybanners'
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
                'source' => 'name'
            ]
        ];
    }

    // SCOPES
    public function scopeActive($query) {
        return $query->where('is_active', '=', 1);
    }


    // RELATIONSHIP
    /**
     * Get the parent that owns the category.
     * @return \App\Models\TalentCategory|null
     */
    public function parent()
    {
        return $this->belongsTo(TalentCategory::class, 'parent_id');
    }

    /**
     * Get children of the category.
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function children()
    {
        return $this->hasMany(TalentCategory::class, 'parent_id')
                    ->orderByRaw('-position_order desc')
                    ->orderBy('name','asc');
    }

    /**
     * Get the expertises in the talentCategory
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function expertises()
    {
        return $this->hasMany(TalentExpertise::class);
    }

    public function curatedExpertises()
    {
        return $this->hasMany(TalentExpertise::class)->where('is_curated', 1);
    }

    // SERVICES
    public static function allCategories()
    {
        return Cache::remember('all_active_categories', 60, function () {
            return static::orderBy('position_order', 'asc')->orderBy('name','asc')->get();
        });
    }

    public static function getHeaderDropDownList()
    {
        return Cache::rememberForever('categories_by_main', function () {
            return TalentCategory::whereNull('parent_id')->with('children')->orderBy('position_order','asc')->orderBy('name','asc')->get();
        });
    }

    public static function getLists()
    {
        $listsGen = TalentCategory::pluck('name', 'id');

        $lists[0] = 'No parent';
        foreach ($listsGen as $key => $value) {
            $lists[$key] = $value;
        }

        return $lists;
    }

    public function getLabel() {
        return "Talent Categories";
    }

    public function getUniqueValue() {
        return $this->name;
    }

    public function getUniqueValueColumn() {
        return 'name';
    }

    public function getFormattedValue() {
        // show up to 3-level for better labeling for general user
        return  ($this->parent && $this->parent->parent ? $this->parent->parent->name. " / " : "") . 
                ($this->parent ? $this->parent->name. " / " : "") . 
                $this->name;
    }

    public function getOptions() {
        return self::all();
    }

    public function getFormattedValueColumn() {
        return ['name'];
    }

    public function getDefaultOrderColumn() {
        return 'name';
    }

    public function getDefaultOrderColumnDirection() {
        return 'asc';
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
            "parent_id" => [
                "type" => self::TYPE_NUMERIC,
                "visible" => true,
                "formdisplay" => true,
                "required" => false,
                "relation" => "parent",
                "label" => "Parent TalentCategory",
                "options" => (new TalentCategory)->orderBy('parent_id','asc')->orderBy('position_order','asc')->orderBy('name','asc')->get()->pluck('default_name','id'),
                "filterable" => true
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
                "label" => "Name",
                "translation" => true
            ],
            "slug" => [
                "type" => self::TYPE_TEXT,
                "visible" => true,
                "formdisplay" => true,
                "required" => false,
                "relation" => null,
                "label" => "Slug"
            ],
            "description" => [
                "type" => self::TYPE_TEXTAREA,
                "visible" => false,
                "formdisplay" => true,
                "required" => false,
                "relation" => null,
                "label" => "Description",
                "translation" => true
            ],
            "cover_image" => [
                "type" => self::TYPE_FILE,
                "visible" => false,
                "formdisplay" => true,
                "required" => false,
                "relation" => null,
                "label" => "Cover/Icon Images"
            ],
            "banner_image" => [
                "type" => self::TYPE_FILE,
                "visible" => false,
                "formdisplay" => true,
                "required" => false,
                "relation" => null,
                "label" => "Banner Images"
            ],
            "is_active" => [
                "type" => self::TYPE_BOOLEAN,
                "visible" => true,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "Is Active?"
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

    // EVENT HANDLER
    protected static function boot() {
        parent::boot();

        static::saved(function ($model) {
            Cache::forget('all_categories');
            Cache::forgeT('descendant_categories_of_'.$model->id);
            Cache::forget('categories_by_'.($model->parent_id ? $model->parent_id : 'main'));
        });

        static::deleted(function ($model) {
            Cache::forget('all_categories');
            Cache::forgeT('descendant_categories_of_'.$model->id);
            Cache::forget('categories_by_'.($model->parent_id ? $model->parent_id : 'main'));
        });
    }
}
