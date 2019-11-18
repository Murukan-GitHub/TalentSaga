<?php

namespace App\Models;

use Cache;
use Suitcore\Models\SuitModel;

/*
|--------------------------------------------------------------------------
| UserSocmedPages Table Structure
|--------------------------------------------------------------------------
| * id INT(10) NOT NULL
| * user_id INT(10) NOT NULL TO ‘users’
| * socmed_name (facebook, twitter, blog_web, instagram)
| * page_url VARCHAR(512) NOT NULL
| * created_at DATETIME NOT NULL
| * updated_at DATETIME NOT NULL
*/
class UserSocmedPage extends SuitModel
{
    // MODEL DEFINITION
    // SOCMED
    const SOCMED_FACEBOOK = 'facebook';
    const SOCMED_TWITTER = 'twitter';
    const SOCMED_INSTAGRAM = 'instagram';
    const SOCMED_BLOG_WEB = 'blog_web';

    public $table = 'user_socmed_pages';
    protected static $bufferAttributeSettings = null;

    public $fillable = [
        'user_id',
        'socmed_name',
        'page_url'
    ];

    public $rules = [
        'user_id'     => 'required',
        'socmed_name' => 'required',
        'page_url'    => 'required'
    ];

    /**
     * Get list of all usersocmed_pages
     *
     * @return array
     */
    public static function allUserSocmedPages()
    {
        return Cache::remember('user_socmed_pages', 60, function(){
            return UserSocmedPage::get();
        });
    }

    /**
     * Get options of Socmed
     *
     * @return array
     */
    public function getSocmedOptions()
    {
        return [self::SOCMED_FACEBOOK => ucfirst(strtolower(self::SOCMED_FACEBOOK)),
                self::SOCMED_TWITTER => ucfirst(strtolower(self::SOCMED_TWITTER)),
                self::SOCMED_INSTAGRAM => ucfirst(strtolower(self::SOCMED_INSTAGRAM)),
                self::SOCMED_BLOG_WEB => ucfirst(strtolower(self::SOCMED_BLOG_WEB)),
        ];
    }

    /**
     * Get label name of Model
     * @return string
     */
    public function getLabel()
    {
        return 'User Socmed Page';
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
                "options" => (new User)->all()->pluck('name','id')
            ],
            'socmed_name' => [
                'type' => self::TYPE_TEXT,
                'visible' => true,
                'formdisplay' => true,
                'required' => true,
                'relation' => null,
                'label' => 'Socmed Name',
                'options' => $this->getSocmedOptions()
            ],
            'page_url' => [
                'type' => self::TYPE_TEXT,
                'visible' => true,
                'formdisplay' => true,
                'required' => true,
                'relation' => null,
                'label' => 'Page URL'
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
}
