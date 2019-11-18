<?php

namespace App\Models;

use Cache;
use Carbon\Carbon;
use Suitcore\Models\SuitModel;

/*
|--------------------------------------------------------------------------
| user_bookings Table Structure
|--------------------------------------------------------------------------
| * id
| * user_id
| * talent_user_id
| * event_title
| * event_detail
| * event_date
| * event_date_start
| * event_date_end
| * event_start_time
| * event_end_time
| * location (string)
| * status : created, approved, rejected, canceled, done
| * talent_rate (integer)
| * talent_review_date (datetime)
| * talent_review (string)
| * created_at (datetime)
| * updated_at (datetime)
*/
class UserBooking extends SuitModel
{
    // MODEL DEFINITION
    const STATUS_CREATED = "created";
    const STATUS_APPROVED = "approved";
    const STATUS_REJECTED = "rejected";
    const STATUS_CANCELED = "canceled";
    const STATUS_DONE = "done";

    public $table = 'user_bookings';

    protected static $bufferAttributeSettings = null;

    public $fillable = [
        'user_id',
        'talent_user_id',
        'event_title',
        'event_detail',
        'event_date_start',
        'event_date_end',
        'event_start_time',
        'event_end_time',
        'location',
        'status',
        'email',
        'phone_number',
        'talent_rate',
        'talent_review_date',
        'talent_review'
    ];

    public $rules = [
        'user_id' => 'required|exists:users,id',
        'talent_user_id' => 'required|exists:users,id',
        'event_title' => 'required',
        'event_detail' => 'required',
        'event_date_start' => 'required',
        'event_date_end' => 'required',
        'location' => 'required',
        'status' => 'required',
        'email' => 'required',
        'phone_number' => 'required'
    ];

    protected $dates = ['event_date_start', 'event_date_end', 'talent_review_date'];

    protected $appends = ['event_date', 'time_duration'];

    public function getEventDateAttribute() {
        return $this->event_date_start;
    }

    public function getTimeDurationAttribute() {
        if ($this->event_date_start) {
            $startTime = Carbon::createFromFormat('Y-m-d H:i:s', ( $this->event_date_start->format('Y-m-d') . " " . ($this->event_start_time ? $this->event_start_time : "00:00:00") ) );
            $endTime = Carbon::createFromFormat('Y-m-d H:i:s', ( ($this->event_date_end ? $this->event_date_end->format('Y-m-d') : $this->event_date_start->format('Y-m-d')) . " " . ($this->event_end_time ? $this->event_end_time : "23:59:59") ) );
            $dayDuration = $endTime->diffInDays($startTime, true);
            $hourDuration = $endTime->diffInHours($startTime, true);
            return ($hourDuration >= 24 ? $dayDuration . " " . trans('label.day') : $hourDuration . " " . trans('label.hour') );
        }
        return "";
    }

    /**
     * Scope Based on Status
     **/
    public function scopeStatus($query, $status) {
        return $query->where('status', $status);
    }

    /**
     * @return User|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return User|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function talentUser()
    {
        return $this->belongsTo(User::class, 'talent_user_id');
    }

    /**
     * Get label name of Model
     * @return string
     */
    public function getLabel()
    {
        return 'Talent Bookings';
    }

    /**
     * Get format text name
     *
     * @return string
     */
    public function getFormattedValue()
    {
        return $this->event_title;
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
        return [self::STATUS_CREATED => ucfirst(strtolower(self::STATUS_CREATED)),
                self::STATUS_APPROVED => ucfirst(strtolower(self::STATUS_APPROVED)),
                self::STATUS_REJECTED => ucfirst(strtolower(self::STATUS_REJECTED)),
                self::STATUS_CANCELED => ucfirst(strtolower(self::STATUS_CANCELED)),
                self::STATUS_DONE => ucfirst(strtolower(self::STATUS_DONE))
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
                'label' => 'Requested By',
                "options" => [],
                "filterable" => true
            ],
            'talent_user_id' => [
                'type' => self::TYPE_NUMERIC,
                'visible' => true,
                'formdisplay' => true,
                'required' => true,
                'relation' => 'talentUser',
                'label' => 'Talent User',
                "options" => [],
                "filterable" => true
            ],
            'event_title' => [
                'type' => self::TYPE_TEXT,
                'visible' => true,
                'formdisplay' => true,
                'required' => true,
                "relation" => null,
                'label' => 'Event Title',
            ],
            'event_detail' => [
                'type' => self::TYPE_TEXTAREA,
                'visible' => false,
                'formdisplay' => true,
                'required' => false,
                "relation" => null,
                'label' => 'Event Detail',
            ],
            'event_date_start' => [
                'type' => self::TYPE_DATE,
                'visible' => true,
                'formdisplay' => true,
                'required' => true,
                "relation" => null,
                'label' => 'Event Date Start',
                "filterable" => true
            ],
            'event_date_end' => [
                'type' => self::TYPE_DATE,
                'visible' => true,
                'formdisplay' => true,
                'required' => true,
                "relation" => null,
                'label' => 'Event Date End',
                "filterable" => true
            ],
            'event_start_time' => [
                'type' => self::TYPE_TIME,
                'visible' => true,
                'formdisplay' => true,
                'required' => false,
                "relation" => null,
                'label' => 'Event Start Time',
            ],
            'event_end_time' => [
                'type' => self::TYPE_TIME,
                'visible' => true,
                'formdisplay' => true,
                'required' => false,
                "relation" => null,
                'label' => 'Event End Time',
            ],
            'location' => [
                'type' => self::TYPE_TEXT,
                'visible' => true,
                'formdisplay' => true,
                'required' => true,
                "relation" => null,
                'label' => 'Location',
            ],
            'status' => [
                "type" => self::TYPE_TEXT,
                "visible" => true,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "Status",
                "options" => $this->getStatusOptions(),
                "filterable" => true
            ],
            'email' => [
                'type' => self::TYPE_TEXT,
                'visible' => false,
                'formdisplay' => true,
                'required' => true,
                "relation" => null,
                'label' => 'Email',
            ],
            'phone_number' => [
                'type' => self::TYPE_TEXT,
                'visible' => false,
                'formdisplay' => true,
                'required' => true,
                "relation" => null,
                'label' => 'Phone Number',
            ],
            'talent_rate' => [
                'type' => self::TYPE_NUMERIC,
                'visible' => true,
                'formdisplay' => true,
                "readonly" => true,
                'required' => false,
                "relation" => null,
                'label' => 'Talent Rate',
            ],
            'talent_review_date' => [
                'type' => self::TYPE_DATETIME,
                'visible' => false,
                'formdisplay' => true,
                "readonly" => true,
                'required' => false,
                "relation" => null,
                'label' => 'Talent Review Date',
            ],
            'talent_review' => [
                "type" => self::TYPE_TEXTAREA,
                'visible' => false,
                'formdisplay' => true,
                "readonly" => true,
                'required' => false,
                "relation" => null,
                'label' => 'Talent Review',
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
            $talentUser = $model->talentUser;
            if ($talentUser) {
                $talentUser->rating = UserBooking::where('user_id', $talentUser->id)->where('talent_rate', '>', 0)->avg('talent_rate');
                $talentUser->save();
            }
        });

        static::deleted(function ($model) {
            $talentUser = $model->talentUser;
            if ($talentUser) {
                $talentUser->rating = UserBooking::where('user_id', $talentUser->id)->where('talent_rate', '>', 0)->avg('talent_rate');
                $talentUser->save();
            }
        });
    }
}
