<?php

namespace App\Models;

use File;
use Suitcore\Models\SuitModel;

/*
|--------------------------------------------------------------------------
| newsletters Table Structure
|--------------------------------------------------------------------------
| * id INT(10) NOT NULL
| * email_sender_name VARCHAR(100)
| * email_subject VARCHAR(100)
| * banner_top_image VARCHAR(45)
| * banner_top_url VARCHAR(255)
| * banner_top_title VARCHAR(100)
| * email_body text
| * banner_bottom_image VARCHAR(45)
| * banner_bottom_url VARCHAR(255)
| * banner_bottom_title VARCHAR(100)
| * limited_start_date DATETIME NULLABLE
| * limited_end_date DATETIME NULLABLE
| * limited_nb_sent INTEGER NULLABLE
| * actual_nb_recepient INTEGER NULLABLE
| * recipient VARCHAR(45)
| * created_at TIMESTAMP
| * updated_at TIMESTAMP
*/
class Newsletter extends SuitModel
{
    const UNLIMITED_SENT = 0;

    const ALL = "all";
    const ADMIN = 'admin';
    const USER = 'user';
    const GUEST_SUBSCRIBER = 'guestsubscriber';
    const NEW_GUEST_SUBSCRIBER = 'newguestsubscriber';

    // status const
    const CREATED = "created";
    const READYTOEXECUTE = 'readytoexecute';
    const EXECUTED = 'executed';

    // MODEL DEFINITION
    public $table = 'newsletters';
    protected static $bufferAttributeSettings = null;

    public $fillable = [
        'email_sender_name',
        'email_subject',
        'banner_top_image',
        'banner_top_url',
        'banner_top_title',
        'email_body',
        'banner_bottom_image',
        'banner_bottom_url',
        'banner_bottom_title',
        'recipient',
        'status',
        'limited_start_date',
        'limited_end_date',
        'limited_nb_sent',
        'actual_nb_recepient'
    ];

    public $rules = [
        'email_sender_name' => 'required',
        'email_subject' => 'required',
        'email_body' => 'required',
    ];

    protected $imageAttributes = [
        'banner_top_image' => 'newsletter',
        'banner_bottom_image' => 'newsletter',
    ];

    protected $files = [
        'banner_top_image' => 'newsletter',
        'banner_bottom_image' => 'newsletter',
    ];

    protected $dates = ['limited_start_date', 'limited_end_date'];

    // SERVICES
    /**
     * Get valid path for banner top image.
     * @return string|bool
     */
    public function getValidBannerTopPath()
    {
        if ($this->banner_top_image != null && !empty($this->banner_top_image)) {
            $targetPath = AppConfig::upload_path() . '/newsletter/' . $this->banner_top_image;
            if (File::exists($targetPath)) {
                return asset('uploads/newsletter/' . $this->banner_top_image);
            }
        }
        return false;
    }

    /**
     * Get valid path for banner banner image.
     * @return string|bool
     */
    public function getValidBannerBottomPath()
    {
        if ($this->banner_bottom_image != null && !empty($this->banner_bottom_image)) {
            $targetPath = AppConfig::upload_path() . '/newsletter/' . $this->banner_bottom_image;
            if (File::exists($targetPath)) {
                return asset('uploads/newsletter/' . $this->banner_bottom_image);
            }
        }
        return false;
    }

    public function getLabel()
    {
        return 'Newsletter';
    }

    public function getFormattedValue()
    {
        return $this->email_subject;
    }

    public function getOptions()
    {
        return self::all();
    }

    public function getRecipientsOptions()
    {
        return [
            static::ALL => "All",
            static::ADMIN => 'Admin',
            static::USER => 'User',
            static::GUEST_SUBSCRIBER => 'Guest Subscriber',
            static::NEW_GUEST_SUBSCRIBER => 'New Guest Subscriber'
        ];
    }

    public function getStatusOptions()
    {
        return [
            static::CREATED => "Created",
            static::READYTOEXECUTE => 'Ready to Execute',
            static::EXECUTED => 'Executed',
        ];
    }

    public function getDefaultOrderColumn() {
        return 'created_at';
    }

    public function getDefaultOrderColumnDirection() {
        return 'desc';
    }

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
            'email_sender_name' => [
                'type' => self::TYPE_TEXT,
                'visible' => true,
                'formdisplay' => true,
                'required' => true,
                'relation' => null,
                'label' => 'Sender Name'
            ],
            'email_subject' => [
                'type' => self::TYPE_TEXT,
                'visible' => true,
                'formdisplay' => true,
                'required' => true,
                'relation' => null,
                'label' => 'Subject'
            ],
            'banner_top_image' => [
                'type' => self::TYPE_FILE,
                'visible' => false,
                'formdisplay' => true,
                'required' => false,
                'relation' => null,
                'label' => 'Top Banner'
            ],
            'banner_top_url' => [
                'type' => self::TYPE_TEXT,
                'visible' => false,
                'formdisplay' => true,
                'required' => false,
                'relation' => null,
                'label' => 'Top Banner URL'
            ],
            'banner_top_title' => [
                'type' => self::TYPE_TEXT,
                'visible' => false,
                'formdisplay' => true,
                'required' => false,
                'relation' => null,
                'label' => 'Top Banner Title'
            ],
            'email_body' => [
                'type' => self::TYPE_RICHTEXTAREA,
                'visible' => false,
                'formdisplay' => true,
                'required' => true,
                'relation' => null,
                'label' => 'Email Body'
            ],
            'recipient' => [
                'type' => self::TYPE_TEXT,
                'visible' => true,
                'formdisplay' => true,
                'required' => true,
                'relation' => null,
                'label' => 'Recipient',
                'options' => $this->getRecipientsOptions(),
                'filterable' => true
            ],
            'banner_bottom_image' => [
                'type' => self::TYPE_FILE,
                'visible' => false,
                'formdisplay' => true,
                'required' => false,
                'relation' => null,
                'label' => 'Bottom Banner'
            ],
            'banner_bottom_url' => [
                'type' => self::TYPE_TEXT,
                'visible' => false,
                'formdisplay' => true,
                'required' => false,
                'relation' => null,
                'label' => 'Bottom Banner URL'
            ],
            'banner_bottom_title' => [
                'type' => self::TYPE_TEXT,
                'visible' => false,
                'formdisplay' => true,
                'required' => false,
                'relation' => null,
                'label' => 'Bottom Banner Title'
            ],
            'status' => [
                'type' => self::TYPE_TEXT,
                'visible' => true,
                'formdisplay' => true,
                'required' => true,
                'relation' => null,
                'label' => 'Status',
                'options' => $this->getStatusOptions(),
                'filterable' => true
            ],
            'limited_start_date' => [
                "type" => self::TYPE_DATETIME,
                "visible" => false,
                "formdisplay" => true,
                "required" => false,
                "relation" => null,
                "label" => "Limited Start Datetime"
            ],
            'limited_end_date' => [
                "type" => self::TYPE_DATETIME,
                "visible" => false,
                "formdisplay" => true,
                "required" => false,
                "relation" => null,
                "label" => "Limited End Datetime"
            ],
            'limited_nb_sent' => [
                'type' => self::TYPE_NUMERIC,
                'visible' => true,
                'formdisplay' => true,
                'required' => false,
                'relation' => null,
                'label' => 'Maximum Recipient Sent',
            ],
            'actual_nb_recepient' => [
                'type' => self::TYPE_NUMERIC,
                'visible' => true,
                'formdisplay' => true,
                'required' => false,
                'relation' => null,
                'label' => 'Current Recipient Sent',
                'readonly' => true
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
