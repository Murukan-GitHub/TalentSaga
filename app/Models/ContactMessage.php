<?php

namespace App\Models;

use Cache;
use Suitcore\Models\SuitModel;

/*
|--------------------------------------------------------------------------
| Contact_Messages Table Structure
|--------------------------------------------------------------------------
| * id INT(10) NOT NULL
| * sender_name VARCHAR(100)
| * sender_email VARCHAR(255) NOT NULL
| * category VARCHAR(48) NOT NULL // options : 'credit_issue', 'website_issue', 'mobile_apps_issue'
| * content VARCHAR(1024) NOT NULL
| * reply VARCHAR(1024) NULLABLE
| * status VARCHAR(50) // options : 'created', 'replied'
| * created_at TIMESTAMP
| * updated_at TIMESTAMP
 */
class ContactMessage extends SuitModel
{
    // Message Status
    const MESSAGE_CREATED = 'created';
    const MESSAGE_REPLIED = 'replied';

    // Category of Message
    const MESSAGE_ISSUE_ACCOUNT     = 'account_issue';
    const MESSAGE_ISSUE_WEBSITE     = 'website_issue';
    const MESSAGE_ISSUE_EMAIL       = 'email_issue';

    // MODEL DEFINITION
    public $table = 'contact_messages';

    protected static $bufferAttributeSettings = null;

    public $fillable = [
        'sender_name',
        'sender_email',
        'category',
        'content',
        'reply',
        'status',
    ];

    public $rules = [
        'sender_email' => 'required|email',
        'category'     => 'required',
        'content'      => 'required',
    ];

    // RELATIONSHIP
    /**
     * Get list of all ContactMessages
     *
     * @return array
     */
    public static function allContactMessages()
    {
        return Cache::remember('contact_messages', 60, function () {
            return ContactMessage::get();
        });
    }

    /**
     * Get List all of ContactMessages
     *
     * @return array
     */
    public static function getHeaderDropDownList()
    {
        $lists            = [];
        $contact_messages = ContactMessage::allContactMessages();

        $lists[0]['key_contact_message']  = 'all';
        $lists[0]['name_contact_message'] = 'All ContactMessage';

        foreach ($contact_messages as $contact_message) {
            $lists[$contact_message->id]['key_contact_message']  = $contact_message->name;
            $lists[$contact_message->id]['name_contact_message'] = $contact_message->name;
        }

        return $lists;
    }

    /**
     * Get label name of Model
     * @return string
     */
    public function getLabel()
    {
        return 'Contact Message';
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
     * List of Messages Issue
     *
     * @return array
     */
    public function getCategoryOptions()
    {
        return [
            self::MESSAGE_ISSUE_ACCOUNT      => trans('label.contactus.'.self::MESSAGE_ISSUE_ACCOUNT),
            self::MESSAGE_ISSUE_WEBSITE     => trans('label.contactus.'.self::MESSAGE_ISSUE_WEBSITE),
            self::MESSAGE_ISSUE_EMAIL => trans('label.contactus.'.self::MESSAGE_ISSUE_EMAIL)
        ];
    }

    /**
     * List Status of Message
     *
     * @return array
     */
    public function getStatusOptions()
    {
        return [
            self::MESSAGE_CREATED => str_text_beautifier(self::MESSAGE_CREATED),
            self::MESSAGE_REPLIED => str_text_beautifier(self::MESSAGE_REPLIED)
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
            'id'           => [
                'type'        => self::TYPE_NUMERIC,
                'visible'     => true,
                'formdisplay' => false,
                'required'    => true,
                'relation'    => null,
                'label'       => 'ID',
            ],
            'sender_name'  => [
                'type'        => self::TYPE_TEXT,
                'visible'     => true,
                'formdisplay' => true,
                'required'    => false,
                'relation'    => null,
                'label'       => 'Sender Name',
            ],
            'sender_email' => [
                'type'        => self::TYPE_TEXT,
                'visible'     => true,
                'formdisplay' => true,
                'required'    => true,
                'relation'    => null,
                'label'       => 'Sender Email',
            ],
            'category'     => [
                'type'        => self::TYPE_TEXT,
                'visible'     => true,
                'formdisplay' => true,
                'required'    => true,
                'relation'    => null,
                'label'       => 'Category',
                // 'options'     => $this->getCategoryOptions(),
                // 'filterable'  => true
            ],
            'content'      => [
                'type'        => self::TYPE_RICHTEXTAREA,
                'visible'     => false,
                'formdisplay' => true,
                'required'    => true,
                'relation'    => null,
                'label'       => 'Content',
            ],
            'reply'        => [
                'type'        => self::TYPE_RICHTEXTAREA,
                'visible'     => false,
                'formdisplay' => true,
                'required'    => false,
                'relation'    => null,
                'label'       => 'Reply',
            ],
            'status'       => [
                'type'        => self::TYPE_TEXT,
                'visible'     => true,
                'formdisplay' => true,
                'required'    => true,
                'relation'    => null,
                'label'       => 'Status',
                'options'     => $this->getStatusOptions(),
                'filterable'  => true
            ],
            'created_at'   => [
                'type'        => self::TYPE_DATETIME,
                'visible'     => false,
                'formdisplay' => false,
                'required'    => false,
                'relation'    => null,
                'label'       => 'Created At',
            ],
            'updated_at'   => [
                'type'        => self::TYPE_DATETIME,
                'visible'     => false,
                'formdisplay' => false,
                'required'    => false,
                'relation'    => null,
                'label'       => 'Updated At',
            ],
        ];
    }
}
