<?php

namespace App\Models;

use DB;
use App;
use Session;
use Cache;
use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Validator;
use Suitcore\Models\SuitModel;

/*
|--------------------------------------------------------------------------
| users Table Structure
|--------------------------------------------------------------------------
- id INT(10) NOT NULL
- username VARCHAR(45) NOT NULL
- password VARCHAR(80) NOT NULL
- picture VARCHAR(255)
- background VARCHAR(255)
- name VARCHAR(255) NOT NULL
- last_name VARCHAR(255) NULLABLE // new added
- birthdate DATE NOT NULL
- email VARCHAR(255) NOT NULL
- phone_number VARCHAR(15)
- registration_date DATETIME NOT NULL
- last_visit qaDATETIME NOT NULL
- status VARCHAR(45)
- remember_token VARCHAR(100)
- forget_password_token
- escrow_amount DOUBLE(15,2)
- rating
- role VARCHAR(50) NOT NULL (admin, user)
- fb_id VARCHAR(255)
- referral_code VARCHAR(32) NULLABLE // new added
- referral_user_id INT(10) NULLABLE TO users // new added
- language_setting VARCHAR(5) NULLABLE // new added
- created_at DATETIME NOT NULL
- updated_at DATETIME NOT NULL
*/
class User extends SuitModel implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable;
    use CanResetPassword;

    // MODEL DEFINITION
    // User Role
    const ADMIN = 'admin';
    const USER = 'user';
    // User Status
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_UNREGISTERED = 'unregistered';
    const STATUS_BANNED = 'banned';
    // User Language Setting
    const LANG_EN = 'en';
    const LANG_DE = 'de';

    public $table = 'users';
    protected static $bufferAttributeSettings = null;

    public $fillable = [
        'username',
        'password',
        'picture',
        'background',
        'name',
        'last_name',
        'birthdate',
        'email',
        'phone_number',
        'registration_date',
        'last_visit',
        'status',
        'remember_token',
        'forget_password_token',
        'rating',
        'escrow_amount',
        'role',
        'fb_id',
        'fb_access_token',
        'li_access_token',
        'gp_id',
        'gp_access_token',
        'referral_code',
        'referral_user_id',
        'language_setting',
        'profile_completion_step',
    ];

    public $rules = [
        'username' => 'required|unique:users|alpha_num',
        'password' => 'required',
        'name' => 'required',
        'role'=>'required',
        'birthdate'=> 'required|date',
        'email' => 'required|email|unique:users',
        'phone_number' => 'required|numeric'
    ];

    protected $hidden = [
        'password',
        'forget_password_token',
        'remember_token'
    ];

    protected $imageAttributes = [
        'picture' => 'profilepicture',
        'background' => 'backgroundpicture'
    ];

    protected $files = [
        'picture' => 'profilepicture',
        'background' => 'backgroundpicture'
    ];

    protected $dates = ['registration_date', 'birthdate'];

    protected $appends = ['full_name', 'age', 'number_of_review', 'actual_rating'];

    // MUTATOR
    public function getActualRatingAttribute() {
        return ($this->rating > 0 ? $this->rating : UserBooking::where('talent_user_id', $this->id)->where('talent_rate', '>', 0)->avg('talent_rate'));
    }

    public function getNumberOfReviewAttribute() {
        return UserBooking::where('talent_user_id', $this->id)->where('talent_rate', '>', 0)->count('id');
    }

    public function getFullNameAttribute() {
        return $this->name . " " . $this->last_name;   
    }

    public function getAgeAttribute() {
        return $this->birthdate ? $this->birthdate->diffInYears() : 0; 
    }

    public function firstImageGallery() {
        return UserGallery::where('user_id', $this->id)->where('type', UserGallery::TYPE_IMAGE)->first();
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'username';
    }

    public function isProfileComplete() {
        $currentUser = $this;
        // dd($currentUser->profile->talentExpertises, count($currentUser->profile->talentExpertises) > 0 );
        return Cache::rememberForever('user_completion_' . $currentUser->id, function() use($currentUser) {
            return (
                !empty($currentUser->name) &&
                !empty($currentUser->last_name) &&
                $currentUser->birthdate &&
                (
                    $currentUser->profile &&
                    !empty($currentUser->profile->talent_profession) &&
                    $currentUser->profile->city &&
                    !empty($currentUser->profile->street_name) &&
                    !empty($currentUser->profile->street_number) &&
                    !empty($currentUser->profile->zip_code) &&
                    !empty($currentUser->profile->gender) &&
                    $currentUser->profile->talentCategory &&
                    $currentUser->profile->talentExpertises &&
                    count($currentUser->profile->talentExpertises) > 0 &&
                    !empty($currentUser->profile->talent_description) &&
                    (
                        (
                            $currentUser->profile->currency &&
                            $currentUser->profile->price_estimation &&
                            $currentUser->profile->pricing_metric
                        ) ||
                        $currentUser->profile->contact_for_price
                    )
                ) &&
                !empty($currentUser->phone_number) &&
                $currentUser->portofolios &&
                count($currentUser->portofolios) > 0 &&
                $currentUser->galleries &&
                count($currentUser->galleries) > 0 &&
                $currentUser->availabilityAreas &&
                count($currentUser->availabilityAreas) > 0
            );
        });
    }

    // RELATIONSHIP
    /**
     * @return UserProfile|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id');
    }

    /**
     * @return UserPriceInclusion |\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userPriceInclusions()
    {
        return $this->hasMany(UserPriceInclusion::class, 'user_id');
    }

    /**
     * @return UserAvailabilityArea |\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userAvailabilityAreas()
    {
        return $this->hasMany(UserAvailabilityArea::class, 'user_id');
    }

    /**
     * @return PriceInclusion | \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function priceInclusions()
    {
        return $this->belongsToMany(PriceInclusion::class, 'user_price_inclusions', 'user_id', 'price_inclusion_id');
    }

    /**
     * @return AvailabilityAreas | \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function availabilityAreas()
    {
        return $this->belongsToMany(City::class, 'user_availability_areas', 'user_id', 'city_id');
    }

    /**
     * @return UserPortofolio |\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function portofolios()
    {
        return $this->hasMany(UserPortofolio::class, 'user_id')->published();
    }

    /**
     * @return UserGallery | \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function galleries()
    {
        return $this->hasMany(UserGallery::class, 'user_id')->published();
    }

    /**
     * @return UserBooking | \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings()
    {
        return $this->hasMany(UserBooking::class, 'user_id');
    }

    /**
     * @return UserBooking | \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
        return $this->hasMany(UserBooking::class, 'talent_user_id')->status(UserBooking::STATUS_DONE)->whereNotNull('talent_review');
    }

    /**
     * @return UserBooking | \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function talentRequests()
    {
        return $this->hasMany(UserBooking::class, 'talent_user_id');
    }

    /**
     * @return UserStory | \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stories()
    {
        return $this->hasMany(UserStory::class, 'user_id');
    }

    // SERVICES
    /**
     * Form Validation for create user.
     * @param  array $data User data from create user form.
     * @return  bool
     */
    public function userDataIsValid($data)
    {
        $validator = Validator::make($data, $this->rules);
        if ($validator->passes()) {
            return true;
        }
        $this->errors = $validator->messages();
        return false;
    }

    public function isGuest()
    {
        // return !($this->role == 'buyer');
        return $this->status == static::STATUS_UNREGISTERED;
    }

    public function scopeUsers($query)
    {
        return $query->where('role', '=', self::USER);
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', '=', self::ADMIN);
    }

    public function scopeActive($query)
    {
        return $query->where('users.status', '=', self::STATUS_ACTIVE);
    }

    public function scopeSimpleSearch($query, $searchTerm) {
        return $query->where(function ($qsearch) use($searchTerm) {
            // name and lastname
            $searchTerm = preg_replace("/[^a-zA-Z0-9\" ]+/", "\\ $0", $searchTerm);
            $searchTerm = str_replace("\\ ", "\\", $searchTerm);
            $searchTerms = implode('|', array_unique( explode(' ', preg_replace('!\s+!', ' ', trim($searchTerm))) ) );
            $qsearch->where('name','rlike',$searchTerms);
            $qsearch->orWhere('last_name','rlike',$searchTerms);

            // on profile level
            $qsearch->orWhereHas('profile', function($qprofile) use($searchTerms) {
                // profession
                $qprofile->where('talent_profession','rlike',$searchTerms);
                // expertise
                $qprofile->orWhereHas('userProfileExpertises', function($qexpertises) use($searchTerms) {
                    $qexpertises->whereHas('talentExpertise', function($qexpertise) use($searchTerms) {
                        $qexpertise->where('name','rlike',$searchTerms);
                        $qexpertise->orWhereHas('category', function($qexpertisecat) use($searchTerms) {
                            $qexpertisecat->where('name','rlike',$searchTerms);
                        });
                    });
                });
                // location
                $qprofile->orWhereHas('city', function($qcity) use($searchTerms) {
                    $qcity->where('name','rlike',$searchTerms);
                });
                $qprofile->orWhereHas('country', function($qcountry) use($searchTerms) {
                    $qcountry->where('name','rlike',$searchTerms);
                });
            });

            // on availability area
            $qsearch->orWhereHas('userAvailabilityAreas', function($qarea) use($searchTerms) {
                $qarea->whereHas('city', function($qcity) use($searchTerms) {
                    $qcity->where('name','rlike',$searchTerms);
                });
            });
        });
    }

    public function isActive()
    {
        return ($this->status == self::STATUS_ACTIVE);
    }

    public function activate()
    {
        $this->status = self::STATUS_ACTIVE;
        $this->save();
    }

    public function deactivate()
    {
        $this->status = self::STATUS_INACTIVE;
        $this->save();
    }

    public function getEscrowAmount()
    {
        return ($this->escrow_amount ? $this->escrow_amount : "0");
    }

    public function getLastVisit()
    {
        $last_visit = date("d F Y H:m:s", strtotime($this->last_visit));
        return $last_visit;
    }

    public function getRegistrationDate()
    {
        $reg_date = date("d F Y", strtotime($this->registration_date));
        return $reg_date;
    }

    public function getFullName()
    {
        return $this->name . " " . $this->last_name;
    }

    public static function getFormalRoleName($role_code)
    {
        $roles = [ ['admin', 'Administrator'], ['user', 'Member'] ];
        foreach ($roles as $role) {
            if ($role_code == $role[0]) {
                return $role[1];
            }
        }
        return "Role not found";
    }

    public function updateLastvisit()
    {
        $date = new Carbon('Asia/Jakarta');
        $this->last_visit = $date;
        $this->save();
        return true;
    }

    public function updateLastActivity(){
        if (!Session::has('last_login') || ((time() - Session::get('last_login')) > (60*10)))
        {
            $this->last_visit = date('Y-m-d H:i:s');
            $this->save();
            Session::put('last_login', time());
        }
    }

    /**
     * Get options of role
     *
     */
    public function getRoleOptions()
    {
        return [self::ADMIN => ucfirst(strtolower(self::ADMIN)),
                self::USER => ucfirst(strtolower(self::USER))
        ];
    }

    public function generateReffCode()
    {
        do {
            $code = generateRandomString(8);
        } while ($this->referral_code == $code);

        $this->update(['referral_code' => $code]);

        return $code;
    }

    public function generateActivationCode()
    {
        if ($this->status != static::STATUS_INACTIVE) {
            return false;
        }

        $code = $this->generateReffCode();
        return md5($code. $this->created_at);
    }

    public function matchActivationCode($code)
    {
        return md5($this->referral_code. $this->created_at) == $code;
    }

    /**
     * Get options of status
     *
     */
    public function getStatusOptions()
    {
        return [self::STATUS_ACTIVE => ucfirst(strtolower(self::STATUS_ACTIVE)),
                self::STATUS_INACTIVE => ucfirst(strtolower(self::STATUS_INACTIVE)),
                self::STATUS_UNREGISTERED => ucfirst(strtolower(self::STATUS_UNREGISTERED))
        ];
    }

    /**
     * Get options of language
     *
     */
    public function getLanguageOptions()
    {
        return [self::LANG_EN => "English - " . strtoupper(self::STATUS_ACTIVE),
                self::LANG_DE => "Germany - " . strtoupper(self::STATUS_INACTIVE)
        ];
    }

    public function getLabel()
    {
        return "User";
    }

    public function getFormattedValue()
    {
        return $this->getFullName();
    }

    public function getOptions()
    {
        return self::all();
    }

    public function getAttributeSettings()
    {
        return [
            "id" => [
                "type" => self::TYPE_NUMERIC,
                "visible" => true,
                "formdisplay" => false,
                "required" => true,
                "relation" => null,
                "label" => "ID"
            ],
            "username" => [
                "type" => self::TYPE_TEXT,
                "visible" => true,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "Username"
            ],
            "password" => [
                "type" => self::TYPE_PASSWORD,
                "visible" => false,
                "formdisplay" => true,
                "required" => false,
                "relation" => null,
                "label" => "Password"
            ],
            "picture" => [
                "type" => self::TYPE_FILE,
                "visible" => false,
                "formdisplay" => true,
                "required" => false,
                "relation" => null,
                "label" => "Profile Picture"
            ],
            "background" => [
                "type" => self::TYPE_FILE,
                "visible" => false,
                "formdisplay" => true,
                "required" => false,
                "relation" => null,
                "label" => "Profile Background"
            ],
            "name" => [
                "type" => self::TYPE_TEXT,
                "visible" => true,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "First Name"
            ],
            "last_name" => [
                "type" => self::TYPE_TEXT,
                "visible" => true,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "Last Name"
            ],
            "birthdate" => [
                "type" => self::TYPE_DATE,
                "visible" => false,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "Birthdate"
            ],
            "role" => [
                "type" => self::TYPE_TEXT,
                "visible" => true,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "Role",
                "options" => $this->getRoleOptions()
            ],
            "email" => [
                "type" => self::TYPE_TEXT,
                "visible" => true,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "Email Address"
            ],
            "phone_number" => [
                "type" => self::TYPE_TEXT,
                "visible" => false,
                "formdisplay" => true,
                "required" => false,
                "relation" => null,
                "label" => "Phone Number"
            ],
            "registration_date" => [
                "type" => self::TYPE_DATETIME,
                "visible" => true,
                "formdisplay" => false,
                "required" => false,
                "relation" => null,
                "label" => "Registration Date",
                "filterable" => true
            ],
            "last_visit" => [
                "type" => self::TYPE_DATETIME,
                "visible" => false,
                "formdisplay" => false,
                "required" => false,
                "relation" => null,
                "label" => "Last Visit"
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
            "rating" => [
                "type" => self::TYPE_FLOAT,
                "visible" => true,
                "formdisplay" => false,
                "required" => false,
                "relation" => null,
                "label" => "User Rating"
            ],
            "escrow_amount" => [
                "type" => self::TYPE_FLOAT,
                "visible" => false,
                "formdisplay" => false,
                "required" => false,
                "relation" => null,
                "label" => "Deposit Amount"
            ],
            "language_setting" => [
                "type" => self::TYPE_TEXT,
                "visible" => true,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "Language Setting",
                "options" => $this->getLanguageOptions()
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

        static::saving(function ($model) {

        });

        static::saved(function ($model) {
            Cache::forget('user_completion_' . $model->id);
        });

        static::deleted(function ($model) {
            Cache::forget('user_completion_' . $model->id);
        });
    }
}
