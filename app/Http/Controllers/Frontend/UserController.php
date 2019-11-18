<?php

namespace App\Http\Controllers\Frontend;

use Input;
use Redirect;
use View;
use Response;
use Auth;
use App;
use Session;
use Hash;
use Mail;
use Validator;
use Event;
use Email;
use FileGrab;
use Upload;
use Exception;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Models\OauthUser;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Country;
use App\Models\City;
use App\Models\Currency;
use App\Models\TalentCategory;
use App\Models\TalentExpertise;
use App\Models\UserProfileExpertise;
use App\Models\PriceInclusion;
use App\Models\UserPortofolio;
use App\Models\UserGallery;
use App\Models\UserPriceInclusion;
use App\Models\UserAvailabilityArea;
use App\Repositories\UserRepository;
use App\Repositories\UserProfileRepository;
use App\Repositories\CountryRepository;
use App\Repositories\CurrencyRepository;
use App\Repositories\PriceInclusionRepository;
use App\Repositories\UserPortofolioRepository;
use App\Repositories\UserGalleryRepository;
use App\Repositories\UserPriceInclusionRepository;
use App\Repositories\UserAvailabilityAreaRepository;

class UserController extends BaseController
{
    use ResetsPasswords;

    protected $userRepo;
    protected $userProfileRepo;
    protected $userPortofolioRepo;
    protected $userGalleryRepo;
    protected $priceInclusionRepo;
    protected $userPriceInclusionRepo;
    protected $countryRepo;
    protected $currencyRepo;
    protected $userAreaRepo;

    public function __construct(UserRepository $_userRepo,
        UserProfileRepository $_userProfileRepo,
        UserPortofolioRepository $_userPortofolioRepo,
        UserGalleryRepository $_userGalleryRepo,
        PriceInclusionRepository $_priceInclusionRepo,
        UserPriceInclusionRepository $_userPriceInclusionRepo,
        CountryRepository $_countryRepo,
        CurrencyRepository $_currencyRepo,
        UserAvailabilityAreaRepository $_userAreaRepo)
    {
        parent::__construct();
        $this->userRepo = $_userRepo;
        $this->userProfileRepo = $_userProfileRepo;
        $this->userPortofolioRepo = $_userPortofolioRepo;
        $this->userGalleryRepo = $_userGalleryRepo;
        $this->priceInclusionRepo = $_priceInclusionRepo;
        $this->userPriceInclusionRepo = $_userPriceInclusionRepo;
        $this->countryRepo = $_countryRepo;
        $this->currencyRepo = $_currencyRepo;
        $this->userAreaRepo = $_userAreaRepo;
    }

    public function getRegistration()
    {
        return view('frontend.user.registration');
    }

    public function postRegistration(Request $request)
    {
        $rules = [
            'username' => 'required',
            'email' => 'required',
            'password' => 'required',
            'password_confirm' => 'required',
        ];

        $this->validate($request, $rules);

        $param = $request->all();
        if ($param['password'] != $param['password_confirm']) return redirect()->back(); // second layer validation

        // Set Up User
        $user = new User();
        $date = new Carbon();
        $userData = array_merge($param, [
                        'role' => User::USER,
                        'name' => $param['username'],
                        'password' => bcrypt($param['password']),
                        'registration_date' => $date,
                        'last_visit' => $date,
                        'birthdate' => '1990-01-01', // default birthday
                        'phone_number' => '0000000000', // default phone number
                        'status' => User::STATUS_INACTIVE, // User::STATUS_ACTIVE,
                        'language_setting' => (isset($param['language_setting']) && !empty($param['language_setting']) && in_array($param['language_setting'], $user->getLanguageOptions()) ? $param['language_setting'] : config('app.fallback_locale', 'en'))
                    ]);
        $user->fill($userData);
        $prevUser = User::where('email',$user->email)->orWhere('username',$user->username)->first();
        if ($prevUser) {
            $this->notifyError(trans('notification.registrationfailed'));
            return redirect()->back()->withInput($request->except('password'));
        }
        $result = $user->save();
        if (!$result) return redirect()->route('frontend.user.registration');
        // Send Email
        if ($user->isActive()) {
            Mail::queue('emails.'.($user->language_setting && in_array($user->language_setting, $user->getLanguageOptions()) ? $user->language_setting : config('app.fallback_locale', 'en')).'.welcome', [
                'name' => $user->name, 'siteurl' => route('frontend.home')
            ], function ($m) use ($user) {
                $m->to($user->email, $user->name)->subject('Talentsaga | Welcome to Talentsaga');
            });
        } else {
            Mail::queue('emails.'.($user->language_setting && in_array($user->language_setting, $user->getLanguageOptions()) ? $user->language_setting : config('app.fallback_locale', 'en')).'.activation', [
                'user' => $user,
                'name' => $user->name,
                'activationLink' => $this->generateActivationLink($user),
            ], function ($m) use ($user) {
                $m->to($user->email, $user->name)->subject('Talentsaga | Welcome to Talentsaga');
            });
        }
        // Next Stage
        $this->notifySuccess(trans('notification.registrationsuccess'));
        // Redirect
        return redirect()->route('frontend.home');
    }

    // BECOME AN ARTIST
    public function getOnboardingPersonal()
    {
        $user = auth()->user();
        $userProfile = $user->profile;
        if (!$userProfile) {
            $userProfile = new UserProfile;
            $userProfile->user_id = $user->id;
        }
        $cities = City::orderBy('country_id', 'asc')->orderBy('name', 'asc')->get();
        return view('frontend.user.onboarding.personal', compact('user', 'userProfile', 'cities'));
    }

    public function postOnboardingPersonal(Request $request)
    {
        // Init User
        $user = auth()->user();
        // Set Up User Profile
        $userProfile = $user->profile;
        if (!$userProfile) {
            $userProfile = new UserProfile;
        }

        // Request Parameter
        $userProfileData = $request->all();
        $isDraft = isset($userProfileData['saveasdraft']) ? $userProfileData['saveasdraft'] : false;

        // Talent Category Initiation if Needed
        $firstCat = TalentCategory::first();
        if (!$firstCat) 
            $firstCat = TalentCategory::create([
                'name' => 'Other Category'
            ]);

        // Talent Expertise Initiation if Needed
        $fistExpertise = TalentExpertise::first();
        if (!$fistExpertise) 
            $fistExpertise = TalentExpertise::create([
                'talent_category_id' => $firstCat->id,
                'name' => 'Other Expertise',
                'is_curated' => false
            ]);

        // Talent Profession
        if (!isset($userProfileData['talent_profession']) || empty($userProfileData['talent_profession'])) {
            $userProfileData['talent_profession'] = ($userProfile->talent_profession ? $userProfile->talent_profession : 'Any Profession');
        }

        // Rest of Profile Data
        $userProfileData = array_merge($userProfileData, [
            'user_id' => $user->id,
            'talent_category_id' => ( $userProfile->talent_category_id ? $userProfile->talent_category_id : $firstCat->id ),
            'talent_expertise_id' => ( $userProfile->talent_expertise_id ? $userProfile->talent_expertise_id : $fistExpertise->id ),
            'price_notes' => ($userProfile->price_notes ? $userProfile->price_notes : 'Contact For Price'),
            'contact_for_price' => ($userProfile->contact_for_price ? $userProfile->contact_for_price : true),
            'status' => ($isDraft ? UserProfile::STATUS_DRAFT : UserProfile::STATUS_PUBLISHED)
        ]);

        // Birthdate
        if (isset($userProfileData['date']) &&
            isset($userProfileData['month']) &&
            isset($userProfileData['year']))
            $userProfileData['birthdate'] = $userProfileData['year'].'-'.str_pad($userProfileData['month'], 2, "0", STR_PAD_LEFT).'-'.str_pad($userProfileData['date'], 2, "0", STR_PAD_LEFT);
        
        /*
        if (isset($userProfileData['country_id']) && $userProfileData['country_id'] && is_numeric($userProfileData['country_id']))
            $userProfileData['country_id'] = str_replace('number:', '', $userProfileData['country_id']);
        else {
            $this->notifyError(trans('notification.countrynotpicked'));
            return redirect()->back()->withInput(request()->all());
        }
        */
        
        // User Profile City
        if (isset($userProfileData['city_id']) && $userProfileData['city_id'] && is_numeric($userProfileData['city_id']))
            $userProfileData['city_id'] = str_replace('number:', '', $userProfileData['city_id']);
        else {
            $this->notifyError(trans('notification.citynotpicked'));
            return redirect()->back()->withInput(request()->all());
        }

        // Save User Profile
        // dd($userProfileData);
        $userProfile->fill($userProfileData);
        $result = $userProfile->save();
        if (!$result) {
            $this->notifyError(trans('notification.personaldatanotupdated'));
            return redirect()->back()->withInput(request()->all());
        }

        // Update User
        $userProfileData['status'] = ($user->status ? $user->status : User::STATUS_ACTIVE);
        $user->fill($userProfileData);
        $result = $user->save();
        if (!$result) {
            $this->notifyError(trans('notification.personaldatanotupdated'));
            return redirect()->back()->withInput(request()->all());
        }
        // Next Stage - Redirect
        $this->notifySuccess(trans('notification.personaldataupdated'));
        return redirect()->route('user.onboarding.talent');
    }

    public function getOnboardingTalent() {
        $user = auth()->user();
        $userProfile = $user->profile;
        if (!$userProfile) {
            $this->notifyMessage(trans('notification.completepersonaldatafirst'));
            return redirect()->route('user.onboarding.personal');
        }
        $userProfileExpertises = $userProfile->talentExpertises;
        $selectedExpertises = $userProfileExpertises->pluck('id')->toArray();
        $selectedExpertises = implode(',', $selectedExpertises);
        $otherSelectedExpertises = [];
        foreach ($userProfileExpertises as $key => $obj) {
            if ($obj->talent_category_id == $userProfile->talent_category_id &&
                !$obj->is_curated) {
                $otherSelectedExpertises[] = $obj->name;
            }
         } 
         $otherSelectedExpertises = implode(',', $otherSelectedExpertises);
        return view('frontend.user.onboarding.talent', compact('user', 'userProfile', 'selectedExpertises', 'otherSelectedExpertises'));
    }

    public function postOnboardingTalent(Request $request) {
        // Init User & Profile
        $user = auth()->user();
        $userProfile = $user->profile;
        if (!$userProfile) {
            $this->notifyMessage(trans('notification.completepersonaldatafirst'));
            return redirect()->route('user.onboarding.personal');
        }

        // Current Expertise
        $userProfileExpertises = $userProfile->userProfileExpertises;
        $userProfileExpertisesIds = $userProfileExpertises->pluck('talent_expertise_id')->toArray();
        foreach ($userProfileExpertisesIds as $key => $value) {
            $userProfileExpertisesIds[$key] = "" . $value;
        }

        // Request Parameter
        $userProfileData = $request->all(); 
        $isDraft = isset($userProfileData['saveasdraft']) ? $userProfileData['saveasdraft'] : false;
        $userProfileData = array_merge($userProfileData, [
            'user_id' => $user->id,
            'status' => ($isDraft ? UserProfile::STATUS_DRAFT : UserProfile::STATUS_PUBLISHED)
        ]);

        // Talent Expertise (curated)
        $talentExpertises = [];
        if (isset($userProfileData['talent_category_id']))
            $userProfileData['talent_category_id'] = str_replace('number:', '', $userProfileData['talent_category_id']);
        if (isset($userProfileData['talent_expertise_id'])) {
            if (is_array($userProfileData['talent_expertise_id'])) {
                $talentExpertises = $userProfileData['talent_expertise_id'];
                $userProfileData['talent_expertise_id'] = str_replace('number:', '', $userProfileData['talent_expertise_id'][0]);
            } else {
                $userProfileData['talent_expertise_id'] = str_replace('number:', '', $userProfileData['talent_expertise_id']);
                $talentExpertises[] = $userProfileData['talent_expertise_id'];
            }
        }

        // Other Talent Expertise
        if (isset($userProfileData['other_talent_expertise_id']) &&
            isset($userProfileData['talent_category_id']) &&
            $userProfileData['talent_category_id']) {
            $otherTalentExpertiseName = $userProfileData['other_talent_expertise_id'];
            $otherTalentExpertiseName = explode(',', $otherTalentExpertiseName);
            unset($userProfileData['other_talent_expertise_id']);
            if ($otherTalentExpertiseName) {
                foreach ($otherTalentExpertiseName as $newName) {
                    $filteredNewName = trim($newName);
                    if (!empty($filteredNewName)) {
                        $talentExpertise = TalentExpertise::where('talent_category_id', '=', $userProfileData['talent_category_id'])->where('name','like',$filteredNewName)->first();
                        if (!$talentExpertise) {
                            $talentExpertise = TalentExpertise::create([
                                'talent_category_id' => $userProfileData['talent_category_id'],
                                'position_order' => 0,
                                'name' => ucwords( strtolower($filteredNewName) ),
                                'is_curated' => false
                            ]);
                        }
                        $talentExpertises[] = "" . $talentExpertise->id;
                    }
                }
            }
        }
        // dd($userProfileExpertises, $userProfileExpertisesIds, $talentExpertises);
        
        // Update User Profile Expertise
        $userProfile->fill($userProfileData);
        $result = $userProfile->save();
        if($result) {
            if ($talentExpertises) {
                foreach ($talentExpertises as $id) {
                    if (!in_array($id, $userProfileExpertisesIds)) {
                        $userProfileExpertise = UserProfileExpertise::create([
                            'user_profile_id' => $userProfile->id,
                            'talent_expertise_id' => $id
                        ]);
                    }
                }
                foreach ($userProfileExpertises as $obj) {
                    if (!in_array("".$obj->talent_expertise_id, $talentExpertises)) {
                        $obj->delete();
                    }
                }
            }
        }

        if (!$result) {
            $this->notifyError(trans('notification.talentdatanotupdated'));
            return redirect()->back()->withInput(request()->all());
        }
        // Next Stage - Redirect
        $this->notifySuccess(trans('notification.talentdataupdated'));
        return redirect()->route('user.onboarding.portofolio');
    }

    public function getOnboardingPortofolio() {
        $user = auth()->user();
        $userProfile = $user->profile;
        if (!$userProfile) {
            $this->notifyMessage(trans('notification.completepersonaldatafirst'));
            return redirect()->route('user.onboarding.personal');
        }
        $userPortofolios = $this->userPortofolioRepo->getByParameter([
                            'user_id' => $user->id,
                            'perPage' => UserPortofolioRepository::FETCH_ALL,
                            'paginate' => false
                        ]);
        return view('frontend.user.onboarding.portofolio', compact('user', 'userProfile', 'userPortofolios'));
    }

    public function postOnboardingPortofolio(Request $request) {
        $user = auth()->user();
        $userProfile = $user->profile;
        if (!$userProfile) {
            $this->notifyMessage(trans('notification.completepersonaldatafirst'));
            return redirect()->route('user.onboarding.personal');
        }
        $userPortofolios = request()->all();
        $isDraft = isset($userPortofolios['saveasdraft']) ? $userPortofolios['saveasdraft'] : false;
        $nbSaved = 0;
        if (isset($userPortofolios['eventDate'])) {
            if (is_array($userPortofolios['eventDate'])) {
                foreach ($userPortofolios['eventDate'] as $key => $eventDate) {
                    if (isset($userPortofolios['eventName'][$key])) {
                        $eventFormattedDate = Carbon::createFromFormat('m/d/Y', $eventDate)->toDateString();
                        $prevPortofolio = $this->userPortofolioRepo->getByParameter([
                            'user_id' => $user->id,
                            'event_date' => $eventFormattedDate,
                            'event_name' => $userPortofolios['eventName'][$key],
                            'perPage' => 1,
                            'paginate' => false
                        ]);
                        if ($prevPortofolio) {
                            $result = $this->userPortofolioRepo->update($prevPortofolio->id, [
                                'description' => (isset($userPortofolios['experience'][$key]) ? $userPortofolios['experience'][$key] : $userPortofolios['eventName'][$key]),
                                'url' => (isset($userPortofolios['eventUrl'][$key]) ? $userPortofolios['eventUrl'][$key] : ''),
                                'youtube_url' => (isset($userPortofolios['eventUrl'][$key]) ? $userPortofolios['eventUrl'][$key] : ''),
                                'status' => ($isDraft ? UserPortofolio::DRAFT_STATUS : UserPortofolio::PUBLISHED_STATUS)
                            ]);
                        } else {
                            $result = $this->userPortofolioRepo->create([
                                'user_id' => $user->id,
                                'event_date' => $eventFormattedDate,
                                'event_name' => $userPortofolios['eventName'][$key],
                                'description' => (isset($userPortofolios['experience'][$key]) ? $userPortofolios['experience'][$key] : $userPortofolios['eventName'][$key]),
                                'url' => (isset($userPortofolios['eventUrl'][$key]) ? $userPortofolios['eventUrl'][$key] : ''),
                                'youtube_url' => (isset($userPortofolios['eventUrl'][$key]) ? $userPortofolios['eventUrl'][$key] : ''),
                                'status' => ($isDraft ? UserPortofolio::DRAFT_STATUS : UserPortofolio::PUBLISHED_STATUS)
                            ]);
                        }
                        if ($result) $nbSaved++;
                    }
                }
            } else {
                if (isset($userPortofolios['eventName'])) {
                    $eventFormattedDate = Carbon::createFromFormat('m/d/Y', $userPortofolios['eventDate'])->toDateString();
                    $prevPortofolio = $this->userPortofolioRepo->getByParameter([
                        'user_id' => $user->id,
                        'event_date' => $eventFormattedDate,
                        'event_name' => $userPortofolios['eventName'],
                        'perPage' => 1,
                        'paginate' => false
                    ]);
                    if ($prevPortofolio) {
                        $result = $this->userPortofolioRepo->update($prevPortofolio->id, [
                            'description' => (isset($userPortofolios['experience']) ? $userPortofolios['experience'] : $userPortofolios['eventName']),
                            'url' => (isset($userPortofolios['eventUrl']) ? $userPortofolios['eventUrl'] : ''),
                            'youtube_url' => (isset($userPortofolios['eventUrl']) ? $userPortofolios['eventUrl'] : ''),
                            'status' => ($isDraft ? UserPortofolio::DRAFT_STATUS : UserPortofolio::PUBLISHED_STATUS)
                        ]);
                    } else {
                        $result = $this->userPortofolioRepo->create([
                            'user_id' => $user->id,
                            'event_date' => $eventFormattedDate,
                            'event_name' => $userPortofolios['eventName'],
                            'description' => (isset($userPortofolios['experience']) ? $userPortofolios['experience'] : $userPortofolios['eventName']),
                            'url' => (isset($userPortofolios['eventUrl']) ? $userPortofolios['eventUrl'] : ''),
                            'youtube_url' => (isset($userPortofolios['eventUrl']) ? $userPortofolios['eventUrl'] : ''),
                            'status' => ($isDraft ? UserPortofolio::DRAFT_STATUS : UserPortofolio::PUBLISHED_STATUS)
                        ]);
                    }
                    if ($result) $nbSaved++;
                }
            }
        }
        // Next Stage - Redirect
        if ($nbSaved) {
            $this->notifySuccess(trans('notification.portofolioupdated'));
            return redirect()->route('user.onboarding.gallery');
        } 
        $this->notifyError(trans('notification.portofolionotupdated'));
        return redirect()->back();
    }

    public function getOnboardingGallery() {
        $user = auth()->user();
        $userProfile = $user->profile;
        if (!$userProfile) {
            $this->notifyMessage(trans('notification.completepersonaldatafirst'));
            return redirect()->route('user.onboarding.personal');
        }
        $userGalleries = $this->userGalleryRepo->getByParameter([
                            'user_id' => $user->id,
                            'perPage' => UserGalleryRepository::FETCH_ALL,
                            'paginate' => false
                        ]);
        return view('frontend.user.onboarding.gallery', compact('user', 'userProfile', 'userGalleries'));
    }

    public function postOnboardingGallery(Request $request) {
        $user = auth()->user();
        $userProfile = $user->profile;
        if (!$userProfile) {
            $this->notifyMessage(trans('notification.completepersonaldatafirst'));
            return redirect()->route('user.onboarding.personal');
        }
        $userGallery = $request->all();
        $isDraft = isset($userGallery['saveasdraft']) ? $userGallery['saveasdraft'] : false;
        $prevGallery = $this->userGalleryRepo->getByParameter([
                            'user_id' => $user->id,
                            'perPage' => UserGalleryRepository::FETCH_ALL,
                            'paginate' => false
                        ]);
        foreach ($prevGallery as $key => $gallery) {
            $gallery->status = $isDraft;
            $gallery->save();
        }
        // begin to save new gallery
        $nbSaved = 0;
        if (isset($userGallery['photos'])) {
            if (is_array($userGallery['photos'])) {
                foreach ($userGallery['photos'] as $key => $photo) {
                    if ($request->photos[$key]) {
                        $result = $this->userGalleryRepo->create([
                            'user_id' => $user->id,
                            'type' => UserGallery::TYPE_IMAGE,
                            'title' => 'Photo of ' . $user->name,
                            'status' => ($isDraft ? UserGallery::DRAFT_STATUS : UserGallery::PUBLISHED_STATUS),
                        ]);
                        if ($result) {
                            $file = $request->photos[$key];
                            $filename = $result->getFormattedValue();
                            $filename = str_limit(str_slug($filename.' '.date('YmdHis')), 200) . '.' . $file->getClientOriginalExtension();
                            $file = $file->move($result->getFolderPath('image_media_url'), $filename);
                            $result->setFile('image_media_url', $file);
                            $result->save();
                        }
                        if ($result) $nbSaved++;
                    }
                }
            }
            if (is_array($userGallery['video'])) {
                foreach ($userGallery['video'] as $key => $video) {
                    $result = $this->userGalleryRepo->create([
                        'user_id' => $user->id,
                        'type' => UserGallery::TYPE_VIDEO,
                        'title' => $video,
                        // 'cover_url' => '',
                        // 'image_media_url' => '',
                        'external_media_url' => $video,
                        'status' => ($isDraft ? UserGallery::DRAFT_STATUS : UserGallery::PUBLISHED_STATUS)
                    ]);
                    if ($result) $nbSaved++;
                }
            } else {
                $result = $this->userGalleryRepo->create([
                    'user_id' => $user->id,
                    'type' => UserGallery::TYPE_VIDEO,
                    'title' => $userGallery['video'],
                    // 'cover_url' => '',
                    // 'image_media_url' => '',
                    'external_media_url' => $userGallery['video'],
                    'status' => ($isDraft ? UserGallery::DRAFT_STATUS : UserGallery::PUBLISHED_STATUS)
                ]);
                if ($result) $nbSaved++;
            }
        }
        // Next Stage - Redirect
        $this->notifySuccess(trans('notification.galleryupdated', ['nbsaved' => $nbSaved]));
        return redirect()->route('user.onboarding.pricing');
    }

    public function getOnboardingPricing() {
        $user = auth()->user();
        $userProfile = $user->profile;
        if (!$userProfile) {
            $this->notifyMessage(trans('notification.completepersonaldatafirst'));
            return redirect()->route('user.onboarding.personal');
        }

        $userPriceInclusions = $user->userPriceInclusions;
        $selectedPriceInclusions = $userPriceInclusions->pluck('price_inclusion_id')->toArray();
        $otherInclusions = [];
        foreach ($userPriceInclusions as $key => $obj) {
            if ($obj->priceInclusion &&
                !$obj->priceInclusion->is_curated) {
                $otherInclusions[] = $obj->priceInclusion->name;
            }
        } 
        $otherInclusions = implode(',', $otherInclusions);

        $availabilityAreas = $user->availabilityAreas;
        $availabilityAreaSet = [];
        foreach ($availabilityAreas as $key => $city) {
            $availabilityAreaSet[$city->id] = $city->getFormattedValue();
        }

        $priceInclusions = $this->priceInclusionRepo->getByParameter([
                            'is_curated' => true,
                            'orderBy' => 'position_order',
                            'orderType' => 'asc',
                            'perPage' => PriceInclusionRepository::FETCH_ALL,
                            'paginate' => false
                        ]);
        $countries= $this->countryRepo->getByParameter([
                            'orderBy' => 'name',
                            'orderType' => 'asc',
                            'perPage' => CountryRepository::FETCH_ALL,
                            'paginate' => false
                        ]);

        $currencies = $this->currencyRepo->cachedList();

        return view('frontend.user.onboarding.pricing', compact('user', 'userProfile', 'priceInclusions', 'otherInclusions', 'countries', 'selectedPriceInclusions', 'availabilityAreaSet', 'currencies'));
    }

    public function postOnboardingPricing(Request $request) {
        $user = auth()->user();
        $userProfile = $user->profile;
        if (!$userProfile) {
            $this->notifyMessage(trans('notification.completepersonaldatafirst'));
            return redirect()->route('user.onboarding.personal');
        }

        $userPriceInclusions = $user->userPriceInclusions;
        $userPriceInclusionIds = array_map(function($val) {
            return "" . $val;
        }, $userPriceInclusions->pluck('price_inclusion_id')->toArray());

        $availabilityAreas = $user->userAvailabilityAreas;
        $availabilityAreaIds = array_map(function($val) {
            return "" . $val;
        }, $availabilityAreas->pluck('city_id')->toArray());

        $pricingProfileData = $request->all();
        // dd($availabilityAreaIds, $userPriceInclusionIds, $pricingProfileData);

        $isDraft = isset($pricingProfileData['saveasdraft']) ? $pricingProfileData['saveasdraft'] : false;
        $pricingProfileData = array_merge($pricingProfileData, [
            'contact_for_price' => (isset($pricingProfileData['contact_for_price']) && $pricingProfileData['contact_for_price']),
            'status' => ($isDraft ? UserProfile::STATUS_DRAFT : UserProfile::STATUS_PUBLISHED)
        ]);
        $result =  $userProfile->update($pricingProfileData);
        if ($result) {
            // PRICE INCLUSION
            $priceInclusionIds = [];
            if (isset($pricingProfileData['inclusion'])) {
                if (is_array($pricingProfileData['inclusion'])) {
                    $priceInclusionIds = $pricingProfileData['inclusion'];
                    $pricingProfileData['inclusion'] = str_replace('number:', '', $pricingProfileData['inclusion'][0]);
                } else {
                    $pricingProfileData['inclusion'] = str_replace('number:', '', $pricingProfileData['inclusion']);
                    $priceInclusionIds[] = $pricingProfileData['inclusion'];
                }
            }
            // ----- Other Price Inclusions
            if (isset($pricingProfileData['other_inclusions'])) {
                $otherPriceInclusions = $pricingProfileData['other_inclusions'];
                $otherPriceInclusions = explode(',', $otherPriceInclusions);
                unset($pricingProfileData['other_inclusions']);
                if ($otherPriceInclusions) {
                    foreach ($otherPriceInclusions as $newName) {
                        $filteredNewName = trim($newName);
                        if (!empty($filteredNewName)) {
                            $priceInclusion = PriceInclusion::where('name','like',$filteredNewName)->first();
                            if (!$priceInclusion) {
                                $priceInclusion = PriceInclusion::create([
                                    'position_order' => 0,
                                    'name' => ucwords( strtolower($filteredNewName) ),
                                    'is_curated' => false
                                ]);
                            }
                            $priceInclusionIds[] = "" . $priceInclusion->id;
                        }
                    }
                }
            }
            // ----- sync
            if ($priceInclusionIds) {
                foreach ($priceInclusionIds as $id) {
                    if (!in_array($id, $userPriceInclusionIds)) {
                        $userPriceInclusion = UserPriceInclusion::create([
                            'user_id' => $user->id,
                            'price_inclusion_id' => $id
                        ]);
                    }
                }
                foreach ($userPriceInclusions as $obj) {
                    if (!in_array("".$obj->price_inclusion_id, $priceInclusionIds)) {
                        $obj->delete();
                    }
                }
            }

            // AREA AVIALIBILITY
            $cityIds = [];
            if (isset($pricingProfileData['city_availability']) &&
                is_array($pricingProfileData['city_availability'])) {
                $cityIds = $pricingProfileData['city_availability'];
            } 
            // ----- sync
            if ($cityIds) {
                foreach ($cityIds as $id) {
                    if (!in_array($id, $availabilityAreaIds)) {
                        $userAvailiability = UserAvailabilityArea::create([
                            'user_id' => $user->id,
                            'city_id' => $id
                        ]);
                    }
                }
                foreach ($availabilityAreas as $obj) {
                    if (!in_array("".$obj->city_id, $cityIds)) {
                        $obj->delete();
                    }
                }
            }

            // return
            $this->notifySuccess(trans('notification.pricingupdated') . " " . trans('notification.profilecomplete'));
            return redirect()->route('frontend.home');
        }
        $this->notifyError(trans('notification.pricingnotupdated'));
        return redirect()->back();
    }
    // END OF BECOME AN ARTIST

    // PROFILE
    public function getMyProfile() {
        $user = auth()->user();
        return $this->getProfile($user->id);
    }

    public function getProfile($userId) {
        $user = User::find($userId);
        $userProfile = $user->profile;
        if (!$userProfile) {
            if (auth()->user() && $userId == auth()->user()->id) {
                $this->notifyError(trans('notification.myprofilenotfound'));
            } else {
                $this->notifyError(trans('notification.profilenotfound'));
            }
            return redirect()->route('frontend.home');
        }
        $userGalleries = $this->userGalleryRepo->getByParameter([
                            'user_id' => $user->id,
                            'perPage' => UserGalleryRepository::FETCH_ALL,
                            'paginate' => false
                        ]);
        return view('frontend.user.profile', compact('user', 'userProfile', 'userGalleries'));
    }

    public function getAccountInformation()
    {
        $user = auth()->user();
        return view('frontend.user.dashboard.account', compact('user', 'userProfile'));
    }

    public function postAccountInformation(Request $request)
    {
        // Init User
        $user = auth()->user();
        // Request Parameter
        $pictureUpdated = false;
        $backgroundUpdated = false;
        $passwordChanged = false;
        $userLangChanged = false;
        if ($request->has('picture')) {
            $param = $request->only(['picture']);
            $pictureUpdated = $this->userRepo->update($user->id, $param, $user);
        }
        if ($request->hasFile('background')) {
            $param = $request->only(['background']);
            $backgroundUpdated = $this->userRepo->update($user->id, $param, $user);
        }
        if ($request->has('current_password') &&
            Hash::check($request->get('current_password'), $user->password) &&
            $request->has('new_password') &&
            $request->has('new_password_confirm') &&
            !empty($request->get('current_password')) &&
            !empty($request->get('new_password')) &&
            ($request->get('new_password') == $request->get('new_password_confirm')) ) {
            $passwordChanged = $this->userRepo->update($user->id, [
                'password' => bcrypt($request->get('new_password'))
            ], $user);
        }
        if ($request->has('language_setting') &&
            !empty($request->get('language_setting')) &&
            $request->get('language_setting') != $user->language_setting) {
            $userLangChanged = $this->userRepo->update($user->id, [
                'language_setting' => $request->get('language_setting')
            ], $user);
        }
        if ($pictureUpdated || $backgroundUpdated || $passwordChanged || $userLangChanged) {
            $updatedFields = [];
            if ($pictureUpdated) $updatedFields[] = 'profile picture';
            if ($backgroundUpdated) $updatedFields[] = 'profile background';
            if ($passwordChanged) $updatedFields[] = 'password';
            if ($userLangChanged) {
                $updatedFields[] = 'language setting';
                $request->session()->forget('custom_locale');
                $request->session()->save();
            }

            $this->notifySuccess(trans('notification.accountdataupdated') . " (" . implode(', ', $updatedFields) . ")");

            if ($passwordChanged) {
                Mail::queue('emails.'.($user->language_setting && in_array($user->language_setting, $user->getLanguageOptions()) ? $user->language_setting : config('app.fallback_locale', 'en')).'.passwordchanged', [
                    'user' => $user,
                    'name' => $user->name
                ], function ($m) use ($user) {
                    $m->to($user->email, $user->name)->subject('Your Password has been changed');
                });
            }

            return redirect()->back();
        }
        $this->notifyError(trans('notification.accountdatanotupdated'));
        return redirect()->back()->withInput(request()->all());
    }

    public function getDashboardPersonal()
    {
        $user = auth()->user();
        $userProfile = $user->profile;
        if (!$userProfile) {
            $userProfile = new UserProfile;
            $userProfile->user_id = $user->id;
        }
        $cities = City::orderBy('country_id', 'asc')->orderBy('name', 'asc')->get();
        return view('frontend.user.dashboard.personal', compact('user', 'userProfile', 'cities'));
    }

    public function postDashboardPersonal(Request $request)
    {
        // Init User
        $user = auth()->user();
        $userProfile = $user->profile;
        if (!$userProfile) {
            $userProfile = new UserProfile;
        }
        // Request Parameter
        $userProfileData = $request->all();
        $isDraft = isset($userProfileData['saveasdraft']) ? $userProfileData['saveasdraft'] : false;

        // Talent Category Initiation if Needed
        $firstCat = TalentCategory::first();
        if (!$firstCat) 
            $firstCat = TalentCategory::create([
                'name' => 'Other Category'
            ]);

        // Talent Expertise Initiation if Needed
        $fistExpertise = TalentExpertise::first();
        if (!$fistExpertise) 
            $fistExpertise = TalentExpertise::create([
                'talent_category_id' => $firstCat->id,
                'name' => 'Other Expertise',
                'is_curated' => false
            ]);

        // Talent Profession
        if (!isset($userProfileData['talent_profession']) || empty($userProfileData['talent_profession'])) {
            $userProfileData['talent_profession'] = ($userProfile->talent_profession ? $userProfile->talent_profession : 'Any Profession');
        }

        // Rest of Profile Data
        $userProfileData = array_merge($userProfileData, [
            'user_id' => $user->id,
            'talent_category_id' => ( $userProfile->talent_category_id ? $userProfile->talent_category_id : $firstCat->id ),
            'talent_expertise_id' => ( $userProfile->talent_expertise_id ? $userProfile->talent_expertise_id : $fistExpertise->id ),
            'price_notes' => ($userProfile->price_notes ? $userProfile->price_notes : 'Contact For Price'),
            'contact_for_price' => ($userProfile->contact_for_price ? $userProfile->contact_for_price : true),
            'status' => ($isDraft ? UserProfile::STATUS_DRAFT : UserProfile::STATUS_PUBLISHED)
        ]);

        if (isset($userProfileData['date']) &&
            isset($userProfileData['month']) &&
            isset($userProfileData['year']))
            $userProfileData['birthdate'] = $userProfileData['year'].'-'.str_pad($userProfileData['month'], 2, "0", STR_PAD_LEFT).'-'.str_pad($userProfileData['date'], 2, "0", STR_PAD_LEFT);
        
        /*
        if (isset($userProfileData['country_id']) && $userProfileData['country_id'] && is_numeric($userProfileData['country_id']))
            $userProfileData['country_id'] = str_replace('number:', '', $userProfileData['country_id']);
        else {
            $this->notifyError(trans('notification.countrynotpicked'));
            return redirect()->back()->withInput(request()->all());
        }
        */

        if (isset($userProfileData['city_id']) && $userProfileData['city_id'] && is_numeric($userProfileData['city_id']))
            $userProfileData['city_id'] = str_replace('number:', '', $userProfileData['city_id']);
        else {
            $this->notifyError(trans('notification.citynotpicked'));
            return redirect()->back()->withInput(request()->all());
        }
        // Set Up User Profile
        $userProfile = $user->profile;
        if (!$userProfile) {
            $userProfile = new UserProfile;
        }
        // dd($userProfileData);
        $userProfile->fill($userProfileData);
        $result = $userProfile->save();
        if (!$result) {
            $this->notifyError(trans('notification.personaldatanotupdated'));
            return redirect()->back()->withInput(request()->all());
        }
        // Update User
        $userProfileData['status'] = User::STATUS_ACTIVE;
        $user->fill($userProfileData);
        $result = $user->save();
        if (!$result) {
            $this->notifyError(trans('notification.personaldatanotupdated'));
            return redirect()->back()->withInput(request()->all());
        }
        // Next Stage - Redirect
        $this->notifySuccess(trans('notification.personaldataupdated'));
        return redirect()->back();
    }

    public function getDashboardTalent() {
        $user = auth()->user();
        $userProfile = $user->profile;
        if (!$userProfile) {
            $this->notifyMessage(trans('notification.completepersonaldatafirst'));
            return redirect()->route('user.dashboard.personal');
        }
        $userProfileExpertises = $userProfile->talentExpertises;
        $selectedExpertises = $userProfileExpertises->pluck('id')->toArray();
        $selectedExpertises = implode(',', $selectedExpertises);
        $otherSelectedExpertises = [];
        foreach ($userProfileExpertises as $key => $obj) {
            if ($obj->talent_category_id == $userProfile->talent_category_id &&
                !$obj->is_curated) {
                $otherSelectedExpertises[] = $obj->name;
            }
         } 
         $otherSelectedExpertises = implode(',', $otherSelectedExpertises);
        return view('frontend.user.dashboard.talent', compact('user', 'userProfile', 'selectedExpertises', 'otherSelectedExpertises'));
    }

    public function postDashboardTalent(Request $request) {
        // Init User
        $user = auth()->user();
        $userProfile = $user->profile;
        if (!$userProfile) {
            $this->notifyMessage(trans('notification.completepersonaldatafirst'));
            return redirect()->route('user.dashboard.personal');
        }
        $userProfileExpertises = $userProfile->userProfileExpertises;
        $userProfileExpertisesIds = $userProfileExpertises->pluck('talent_expertise_id')->toArray();
        foreach ($userProfileExpertisesIds as $key => $value) {
            $userProfileExpertisesIds[$key] = "" . $value;
        }
        // Request Parameter
        $userProfileData = $request->all();
        $isDraft = isset($userProfileData['saveasdraft']) ? $userProfileData['saveasdraft'] : false;
        $userProfileData = array_merge($userProfileData, [
            'user_id' => $user->id,
            'status' => ($isDraft ? UserProfile::STATUS_DRAFT : UserProfile::STATUS_PUBLISHED)
        ]);
        $talentExpertises = [];
        if (isset($userProfileData['talent_category_id']))
            $userProfileData['talent_category_id'] = str_replace('number:', '', $userProfileData['talent_category_id']);
        if (isset($userProfileData['talent_expertise_id'])) {
            if (is_array($userProfileData['talent_expertise_id'])) {
                $talentExpertises = $userProfileData['talent_expertise_id'];
                $userProfileData['talent_expertise_id'] = str_replace('number:', '', $userProfileData['talent_expertise_id'][0]);
            } else {
                $userProfileData['talent_expertise_id'] = str_replace('number:', '', $userProfileData['talent_expertise_id']);
                $talentExpertises[] = $userProfileData['talent_expertise_id'];
            }
        }
        // Other Talent Expertise
        if (isset($userProfileData['other_talent_expertise_id']) &&
            isset($userProfileData['talent_category_id']) &&
            $userProfileData['talent_category_id']) {
            $otherTalentExpertiseName = $userProfileData['other_talent_expertise_id'];
            $otherTalentExpertiseName = explode(',', $otherTalentExpertiseName);
            unset($userProfileData['other_talent_expertise_id']);
            if ($otherTalentExpertiseName) {
                foreach ($otherTalentExpertiseName as $newName) {
                    $filteredNewName = trim($newName);
                    if (!empty($filteredNewName)) {
                        $talentExpertise = TalentExpertise::where('talent_category_id', '=', $userProfileData['talent_category_id'])->where('name','like',$filteredNewName)->first();
                        if (!$talentExpertise) {
                            $talentExpertise = TalentExpertise::create([
                                'talent_category_id' => $userProfileData['talent_category_id'],
                                'position_order' => 0,
                                'name' => ucwords( strtolower($filteredNewName) ),
                                'is_curated' => false
                            ]);
                        }
                        $talentExpertises[] = "" . $talentExpertise->id;
                    }
                }
            }
        }
        // dd($userProfileExpertises, $userProfileExpertisesIds, $talentExpertises);
        // Update User Profile
        $userProfile->fill($userProfileData);
        $result = $userProfile->save();
        if($result) {
            if ($talentExpertises) {
                foreach ($talentExpertises as $id) {
                    if (!in_array($id, $userProfileExpertisesIds)) {
                        $userProfileExpertise = UserProfileExpertise::create([
                            'user_profile_id' => $userProfile->id,
                            'talent_expertise_id' => $id
                        ]);
                    }
                }
                foreach ($userProfileExpertises as $obj) {
                    if (!in_array("".$obj->talent_expertise_id, $talentExpertises)) {
                        $obj->delete();
                    }
                }
            }
        }
        if (!$result) {
            $this->notifyError(trans('notification.talentdatanotupdated'));
            return redirect()->back()->withInput(request()->all());
        }
        // Next Stage - Redirect
        $this->notifySuccess(trans('notification.talentdataupdated'));
        return redirect()->back();
    }

    public function getDashboardPortofolio() {
        $user = auth()->user();
        $userProfile = $user->profile;
        if (!$userProfile) {
            $this->notifyMessage(trans('notification.completepersonaldatafirst'));
            return redirect()->route('user.dashboard.personal');
        }
        $baseModel = new UserPortofolio;
        $userPortofolios = $this->userPortofolioRepo->getByParameter([
                            'user_id' => $user->id,
                            'perPage' => 10,
                            'paginate' => true
                        ]);
        return view('frontend.user.dashboard.portofolio', compact('baseModel', 'user', 'userProfile', 'userPortofolios'));
    }

    public function getDashboardPortofolioEdit($id) {
        $user = auth()->user();
        $userProfile = $user->profile;
        if (!$userProfile) {
            $this->notifyMessage(trans('notification.completepersonaldatafirst'));
            return redirect()->route('user.dashboard.personal');
        }
        $userPortofolio = UserPortofolio::find($id);
        if ($userPortofolio && $userPortofolio->user_id == $user->id) {
            return view('frontend.user.dashboard.portofolioedit', compact('user', 'userProfile', 'userPortofolio'));
        } 
        return App::abort(404);
    }

    public function postDashboardPortofolio(Request $request) {
        $user = auth()->user();
        $userProfile = $user->profile;
        if (!$userProfile) {
            $this->notifyMessage(trans('notification.completepersonaldatafirst'));
            return redirect()->route('user.dashboard.personal');
        }
        $userPortofolios = array_merge($request->all(), [
            'user_id' => $user->id
        ]);
        if (isset($userPortofolios['event_date']) && !empty($userPortofolios['event_date'])) {
            $eventFormattedDate = Carbon::createFromFormat('m/d/Y', $userPortofolios['event_date'])->toDateString();
            $userPortofolios = array_merge($userPortofolios, [
                'event_date' => $eventFormattedDate,
            ]);
        }
        $result = $this->userPortofolioRepo->create($userPortofolios);
        // Return Result
        if ($result) {
            $this->notifySuccess(trans('notification.portofoliocreated'));
            return redirect()->back();
        } else {
            $this->notifyError(trans('notification.portofolionotcreated'));
            return redirect()->back();
        }
    }

    public function postDashboardPortofolioEdit($id, Request $request) {
        $user = auth()->user();
        $userProfile = $user->profile;
        if (!$userProfile) {
            $this->notifyMessage(trans('notification.completepersonaldatafirst'));
            return redirect()->route('user.dashboard.personal');
        }
        $userPortofolios = $request->all();
        $prevPortofolio = $this->userPortofolioRepo->get($id);
        if (!$prevPortofolio['object'] || ($prevPortofolio['object'] && $prevPortofolio['object']->user_id != $user->id)) {
            return App::abort(404);
        }
        $userPortofolios = array_merge($request->all(), [
            'user_id' => $user->id
        ]);
        if (isset($userPortofolios['event_date']) && !empty($userPortofolios['event_date'])) {
            $eventFormattedDate = Carbon::createFromFormat('m/d/Y', $userPortofolios['event_date'])->toDateString();
            $userPortofolios = array_merge($userPortofolios, [
                'event_date' => $eventFormattedDate,
            ]);
        }
        $result = $this->userPortofolioRepo->update($prevPortofolio['object']->id, $userPortofolios);
        // Return Result
        if ($result) {
            $this->notifySuccess(trans('notification.portofolioupdated'));
            return redirect()->route('user.dashboard.portofolio');
        } else {
            $this->notifyError(trans('notification.portofolionotupdated'));
            return redirect()->back();
        }
    }

    public function postDashboardPortofolioDelete($id) {
        $user = auth()->user();
        $userProfile = $user->profile;
        if (!$userProfile) {
            $this->notifyMessage(trans('notification.completepersonaldatafirst'));
            return redirect()->route('user.dashboard.personal');
        }
        $prevPortofolio = $this->userPortofolioRepo->get($id);
        if (!$prevPortofolio['object'] || ($prevPortofolio['object'] && $prevPortofolio['object']->user_id != $user->id)) {
            return App::abort(404);
        }
        $result = $this->userPortofolioRepo->delete($prevPortofolio['object']->id);
        // Return Result
        if ($result) {
            $this->notifySuccess(trans('notification.portofoliodeleted'));
            return redirect()->back();
        }
        $this->notifyError(trans('notification.portofolionotdeleted'));
        return redirect()->back();
    }    

    public function getDashboardGallery() {
        $user = auth()->user();
        $userProfile = $user->profile;
        if (!$userProfile) {
            $this->notifyMessage(trans('notification.completepersonaldatafirst'));
            return redirect()->route('user.dashboard.personal');
        }
        $order = request()->get('order', 'position');
        $type = request()->get('type', null);
        $filterParam = [];
        if ($order) {
            $filterParam['orderBy'] = ($order == 'position' ? 'position_order' : 'created_at');
            $filterParam['orderType'] = ($order == 'newest' ? 'desc' : 'asc');
        }
        if ($type) $filterParam['type'] = $type;
        $userGalleries = $this->userGalleryRepo->getByParameter(array_merge($filterParam, [
                            'user_id' => $user->id,
                            'perPage' => 12,
                            'paginate' => true
                        ]));
        return view('frontend.user.dashboard.gallery', compact('user', 'userProfile', 'userGalleries'));
    }

    public function postDashboardGallery(Request $request) {
        $user = auth()->user();
        $userProfile = $user->profile;
        if (!$userProfile) {
            $this->notifyMessage(trans('notification.completepersonaldatafirst'));
            return redirect()->route('user.dashboard.personal');
        }
        $userGalleries = $request->all();

        if (isset($userGalleries['type'])) {
            if ($userGalleries['type'] == UserGallery::TYPE_IMAGE) {
                $imageTitles = isset($userGalleries['imagefilenames']) ? $userGalleries['imagefilenames'] : null;
                $imageBytes = isset($userGalleries['imagefiles']) ? $userGalleries['imagefiles'] : null;
                if ($imageTitles &&
                    $imageBytes &&
                    is_array($imageTitles) &&
                    is_array($imageBytes) &&
                    count($imageTitles) == count($imageBytes)) {
                    $nbSaved = 0;
                    foreach ($imageTitles as $idx => $imageTitle) {
                        // begin to save new gallery
                        if (isset($userGalleries['type'])) {
                            if ($userGalleries['type'] == UserGallery::TYPE_IMAGE) {
                                $userGallery = [
                                    'user_id' => $user->id,
                                    'type' => UserGallery::TYPE_IMAGE,
                                    'title' => $imageTitle,
                                    'status' => $userGalleries['status'],
                                    'image_media_url' => $imageBytes[$idx]
                                ];
                                $result = $this->userGalleryRepo->create($userGallery);
                                if ($result) {
                                    $nbSaved++;
                                } 
                            } 
                        }
                    }
                    if ($nbSaved) {
                        $this->notifySuccess(trans('notification.imagegallerycreated', ['nbsaved' => $nbSaved]));
                    } else {
                        $this->notifyError(trans('notification.imagegallerynotcreated'));
                    }
                }
            } else {
                $userGalleries['user_id'] = $user->id;
                $result = $this->userGalleryRepo->create($userGalleries);
                if ($result) {
                    $this->notifySuccess(trans('notification.videogallerycreated'));
                } else {
                    $this->notifyError(trans('notification.videogallerynotcreated'));
                }
            } 
        } else {
            $this->notifyError(trans('notification.videogallerynotcreated'));
        }

        // Redirect
        return redirect()->route('user.dashboard.gallery');
    }

    public function getDashboardGalleryEdit($id, Request $request) {
        $user = auth()->user();
        $userProfile = $user->profile;
        if (!$userProfile) {
            $this->notifyMessage(trans('notification.completepersonaldatafirst'));
            return redirect()->route('user.dashboard.personal');
        }
        $userGallery = UserGallery::find($id);
        if ($userGallery && $userGallery->user_id == $user->id) {
            return view('frontend.user.dashboard.editgallery', compact('user', 'userProfile', 'userGallery'));
        } 
        return App::abort(404);
    }

    public function postDashboardGalleryEdit($id, Request $request) {
        $user = auth()->user();
        $userProfile = $user->profile;
        if (!$userProfile) {
            $this->notifyMessage(trans('notification.completepersonaldatafirst'));
            return redirect()->route('user.dashboard.personal');
        }
        $param = $request->all();
        $userGallery = UserGallery::find($id);
        if ($userGallery && $userGallery->user_id == $user->id) {
            // begin to update gallery
            if (isset($userGallery['type'])) {
                if ($userGallery['type'] == UserGallery::TYPE_IMAGE) {
                    $result = $this->userGalleryRepo->update($userGallery->id, $param);
                    if ($result) {
                        $this->notifySuccess(trans('notification.imagegalleryupdated'));
                    } else {
                        $this->notifyError(trans('notification.imagegallerynotupdated'));
                    }
                } else {
                    $result = $this->userGalleryRepo->update($userGallery->id, $param);
                    if ($result) {
                        $this->notifySuccess(trans('notification.videogalleryupdated'));
                    } else {
                        $this->notifyError(trans('notification.videogallerynotupdated'));
                    }
                }
            }
        } else {
            $this->notifyError(trans('notification.gallerynotfound'));
        }
        // Redirect
        return redirect()->route('user.dashboard.gallery');
    }

    public function postDashboardGalleryDelete($id, Request $request) {
        $user = auth()->user();
        $param = $request->all();
        $userGallery = UserGallery::find($id);
        if ($userGallery && $userGallery->user_id == $user->id) {
            $result = $userGallery->delete();
            if ($result) {
                $this->notifySuccess(trans('notification.gallerydeleted'));
            } else {
                $this->notifyError(trans('notification.gallerynotdeleted'));
            }
        } else {
            $this->notifyError(trans('notification.gallerynotfound'));
        }
        // Redirect
        return redirect()->route('user.dashboard.gallery');
    }

    public function getDashboardPricing() {
        $user = auth()->user();
        $userProfile = $user->profile;
        if (!$userProfile) {
            $this->notifyMessage(trans('notification.completepersonaldatafirst'));
            return redirect()->route('user.dashboard.personal');
        }

        $userPriceInclusions = $user->userPriceInclusions;
        $selectedPriceInclusions = $userPriceInclusions->pluck('price_inclusion_id')->toArray();
        $otherInclusions = [];
        foreach ($userPriceInclusions as $key => $obj) {
            if ($obj->priceInclusion &&
                !$obj->priceInclusion->is_curated) {
                $otherInclusions[] = $obj->priceInclusion->name;
            }
        } 
        $otherInclusions = implode(',', $otherInclusions);

        $availabilityAreas = $user->availabilityAreas;
        $availabilityAreaSet = [];
        foreach ($availabilityAreas as $key => $city) {
            $availabilityAreaSet[$city->id] = $city->getFormattedValue();
        }

        $priceInclusions = $this->priceInclusionRepo->getByParameter([
                            'is_curated' => true,
                            'orderBy' => 'position_order',
                            'orderType' => 'asc',
                            'perPage' => PriceInclusionRepository::FETCH_ALL,
                            'paginate' => false
                        ]);
        $countries= $this->countryRepo->getByParameter([
                            'orderBy' => 'name',
                            'orderType' => 'asc',
                            'perPage' => CountryRepository::FETCH_ALL,
                            'paginate' => false
                        ]);
        
        $currencies = $this->currencyRepo->cachedList();

        return view('frontend.user.dashboard.pricing', compact('user', 'userProfile', 'priceInclusions', 'otherInclusions', 'countries', 'selectedPriceInclusions', 'availabilityAreaSet', 'currencies'));
    }

    public function postDashboardPricing(Request $request) {
        $user = auth()->user();
        $userProfile = $user->profile;
        if (!$userProfile) {
            $this->notifyMessage(trans('notification.completepersonaldatafirst'));
            return redirect()->route('user.dashboard.personal');
        }

        $userPriceInclusions = $user->userPriceInclusions;
        $userPriceInclusionIds = $userPriceInclusions->pluck('price_inclusion_id')->toArray();
        foreach ($userPriceInclusionIds as $key => $value) {
            $userPriceInclusionIds[$key] = "" . $value;
        }

        $availabilityAreas = $user->userAvailabilityAreas;
        $availabilityAreaIds = array_map(function($val) {
            return "" . $val;
        }, $availabilityAreas->pluck('city_id')->toArray());

        $pricingProfileData = $request->all();
        // dd($pricingProfileData);
        $isDraft = isset($pricingProfileData['saveasdraft']) ? $pricingProfileData['saveasdraft'] : false;
        $pricingProfileData = array_merge($pricingProfileData, [
            'contact_for_price' => (isset($pricingProfileData['contact_for_price']) && $pricingProfileData['contact_for_price']),
            'status' => ($isDraft ? UserProfile::STATUS_DRAFT : UserProfile::STATUS_PUBLISHED)
        ]);
        $result =  $userProfile->update($pricingProfileData);
        if ($result) {
            // PRICE INCLUSION
            $priceInclusionIds = [];
            if (isset($pricingProfileData['inclusion'])) {
                if (is_array($pricingProfileData['inclusion'])) {
                    $priceInclusionIds = $pricingProfileData['inclusion'];
                    $pricingProfileData['inclusion'] = str_replace('number:', '', $pricingProfileData['inclusion'][0]);
                } else {
                    $pricingProfileData['inclusion'] = str_replace('number:', '', $pricingProfileData['inclusion']);
                    $priceInclusionIds[] = $pricingProfileData['inclusion'];
                }
            }
            // ----- Other Price Inclusions
            if (isset($pricingProfileData['other_inclusions'])) {
                $otherPriceInclusions = $pricingProfileData['other_inclusions'];
                $otherPriceInclusions = explode(',', $otherPriceInclusions);
                unset($pricingProfileData['other_inclusions']);
                if ($otherPriceInclusions) {
                    foreach ($otherPriceInclusions as $newName) {
                        $filteredNewName = trim($newName);
                        if (!empty($filteredNewName)) {
                            $priceInclusion = PriceInclusion::where('name','like',$filteredNewName)->first();
                            if (!$priceInclusion) {
                                $priceInclusion = PriceInclusion::create([
                                    'position_order' => 0,
                                    'name' => ucwords( strtolower($filteredNewName) ),
                                    'is_curated' => false
                                ]);
                            }
                            $priceInclusionIds[] = "" . $priceInclusion->id;
                        }
                    }
                }
            }
            // ----- sync
            if ($priceInclusionIds) {
                foreach ($priceInclusionIds as $id) {
                    if (!in_array($id, $userPriceInclusionIds)) {
                        $userPriceInclusion = UserPriceInclusion::create([
                            'user_id' => $user->id,
                            'price_inclusion_id' => $id
                        ]);
                    }
                }
                foreach ($userPriceInclusions as $obj) {
                    if (!in_array("".$obj->price_inclusion_id, $priceInclusionIds)) {
                        $obj->delete();
                    }
                }
            }

            // AREA AVIALIBILITY
            $cityIds = [];
            if (isset($pricingProfileData['city_availability'])) {
                if (is_array($pricingProfileData['city_availability'])) {
                    $cityNames = $pricingProfileData['city_availability'];
                    $pricingProfileData['city_availability'] = str_replace('number:', '', $pricingProfileData['city_availability'][0]);
                    foreach ($cityNames as $key => $value) {
                        $perCountryCityNames = explode(',', $value);
                        foreach ($perCountryCityNames as $key => $cityName) {
                            $tmpCity = City::where('name','like',$cityName)->first();
                            if ($tmpCity) {
                                $cityIds[] = $tmpCity->id;
                            }
                        }
                    }
                } else {
                    $pricingProfileData['city_availability'] = str_replace('number:', '', $pricingProfileData['city_availability']);
                    $cityNames = $pricingProfileData['city_availability'];
                    $perCountryCityNames = explode(',', $cityNames);
                    foreach ($perCountryCityNames as $key => $cityName) {
                        $tmpCity = City::where('name','like',$cityName)->first();
                        if ($tmpCity) {
                            $cityIds[] = $tmpCity->id;
                        }
                    }
                }
            }
            // ----- sync
            if ($cityIds) {
                foreach ($cityIds as $id) {
                    if (!in_array($id, $availabilityAreaIds)) {
                        $userAvailiability = UserAvailabilityArea::create([
                            'user_id' => $user->id,
                            'city_id' => $id
                        ]);
                    }
                }
                foreach ($availabilityAreas as $obj) {
                    if (!in_array("".$obj->city_id, $cityIds)) {
                        $obj->delete();
                    }
                }
            }

            // return
            $this->notifySuccess(trans('notification.pricingupdated'));
            return redirect()->back();
        }
        $this->notifyError(trans('notification.pricingnotupdated'));
        return redirect()->back();
    }

    // OTHERS
    protected function generateActivationLink($user)
    {
        $code = $user->generateActivationCode();
        $id = $user->id;

        return route('frontend.user.activation', compact('id', 'code'));
    }

    public function editPassword()
    {
        $user = auth()->user();

        return view('frontend.user.editPassword', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $rules = [
            'oldpassword' => 'required',
            'newpassword' => 'required|confirmed',
        ];

        $this->validate($request, $rules);
        extract($request->all());

        $user = auth()->user();
        if (!Hash::check($oldpassword, $user->password)) {
            $this->notifyError(trans('notification.passwordupdatefailed'));
            return redirect()->back();
        }
        $user->update(['password' => bcrypt($newpassword)]);

        $this->notifySuccess(trans('notification.passwordupdatesuccess'));
        return redirect()->route('sessions.login');
    }

    public function wishlist(Request $request, WishlistRepository $wishlist)
    {
        $user = auth()->user();
        $products = $wishlist->fromUser($user, $request->all());

        return view('frontend.user.wishlist', compact('products'));
    }

    public function addWishlist(WishlistRepository $wishlist, ProductRepository $product, $productId)
    {
        $product = ProductRepository::get($productId);
        $wishlist->add($product);
    }

    public function produceHashCode($id)
    {
        $user = User::find($id);

        $created_at = strtotime($user->created_at);
        $formatted_time = date('dmYhis', $created_at);

        $hashed = $user->username.'-created'.$formatted_time.'-'.$user->email;
        $hashcode = md5($hashed);

        return $hashcode;
    }

    public function getForgetPassword()
    {
        view()->share('pageTitle', 'Forgot Password');
        return View::make('frontend.user.forgetpassword');
    }

    public function postForgetPassword(Request $request)
    {
        return $this->sendResetLinkEmail($request);
    }

    public function getConfirmResetPassword(Request $request, $token = null)
    {
        $this->resetView = 'frontend.user.confirmforgetpassword';
        return $this->showResetForm($request, $token);
    }

    public function postConfirmResetPassword(Request $request)
    {
        $this->redirectPath = route('sessions.login');
        return $this->reset($request);
    }

    protected function resetPassword($user, $password)
    {
        $user->forceFill([
            'password' => bcrypt($password),
            'remember_token' => Str::random(60),
        ])->save();

        // Email Notify
        try {
            Mail::queue('emails.'.($user->language_setting && in_array($user->language_setting, $user->getLanguageOptions()) ? $user->language_setting : config('app.fallback_locale', 'en')).'.passwordchanged', [
                'name' => $user->name
            ], function ($m) use ($user) {
                $m->to($user->email, $user->name)->subject('Your Password has been changed');
            });
        } catch (Exception $e) { }

        Auth::guard($this->getGuard())->login($user);
        return redirect()->route('sessions.login');
    }

    /**
     * Get the response for after a successful password reset.
     *
     * @param  string  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getResetSuccessResponse($response)
    {
        session(['message' => 'Successfully Reset Your Password']);
        return redirect($this->redirectPath())->with('status', trans($response));
    }

    public function getUserActivation(Request $request)
    {
        $email = $request->get('email');
        
        if (!$email) {
            return view('frontend.user.reactivationForm', compact('email'));
        }

        $user = User::where('email', $email)
                ->where('status', User::STATUS_INACTIVE)
                ->first();

        if ($user && $code = $user->generateActivationCode()) {
            $email = $user->email;
            Mail::queue('emails.'.($user->language_setting && in_array($user->language_setting, $user->getLanguageOptions()) ? $user->language_setting : config('app.fallback_locale', 'en')).'.reactivation', [
                'user' => $user,
                'name' => $user->name,
                'activationLink' => $this->generateActivationLink($user),
            ], function ($m) use ($user) {
                $m->to($user->email, $user->name)->subject('Talentsaga | Reactivate Your Account');
            });
            return view('frontend.user.reactivationSuccess', compact('email'));
        }
        return view('frontend.user.reactivationFailed', compact('email'));
    }

    public function activateAccount($id, $hashcode)
    {
        $user = User::find($id);
        if ($user == null) {
            Session::put('salahaktivasi', true);
            return Redirect::route('frontend.user.activation.failed');
        } else {
            if ($user->matchActivationCode($hashcode)) {
                // Change user status
                $user->status = User::STATUS_ACTIVE;
                $user->save();
                Session::put('aktivasi', true);

                // Send Welcome Email
                Mail::queue('emails.'.($user->language_setting && in_array($user->language_setting, $user->getLanguageOptions()) ? $user->language_setting : config('app.fallback_locale', 'en')).'.welcome', [
                    'name' => $user->name
                ], function ($m) use ($user) {
                    $m->to($user->email, $user->name)->subject('Talentsaga | Welcome to Talentsaga');
                });

                // Redirect to Activation Success
                return Redirect::route('frontend.user.activation.success');
            } else {
                Session::put('salahaktivasi', true);
                return Redirect::route('frontend.user.activation.failed');
            }
        }
    }

    public function activateAccountSuccess()
    {
        view()->share('pageTitle', 'Activation Success');
        return view('frontend.user.activationSuccess');
    }

    public function activateAccountFailed()
    {
        view()->share('pageTitle', 'Activation Failed');
        return view('frontend.user.activationFailed');
    }

    public function getCompleteregistration()
    {
        $user = Auth::user();
        Session::put('fb', true);
        $location = Location::find($user->location_id);
        if (empty($location)) {
            $address_province = '';
            $address_city = '';
            $address_kecamatan = '';
            $address_kelurahan = '';
        } else {
            $address_province = $location->province_id;
            $address_city = $location->city_id;
            $address_kecamatan = $location->kecamatan_id;
            $address_kelurahan = $location->kelurahan_id;
        }

        return View::make('frontend.user.registration')->with(array('user' => $user, 'address_province' => $address_province, 'address_city' => $address_city, 'address_kecamatan' => $address_kecamatan, 'address_kelurahan' => $address_kelurahan));
    }

    public function postCompleteregistration()
    {
        $input = request()->all();
        $user = Auth::user();

        if (request()->get('password') != request()->get('repassword')) {
            Session::put('passwordnotsame', true);

            return Redirect::route('frontend.member.completeregistration')->with(array('errors' => $user->getError()))->withInput(request()->except('password'));
        }
        if (request()->get('agree') == null) {
            Session::put('notagree', true);

            return Redirect::route('frontend.member.completeregistration')->with(array('errors' => $user->getError()))->withInput(request()->except('password'));
        }

        if (request()->has('address_province') && request()->has('address_city') && request()->has('address_kelurahan') && request()->has('address_kecamatan') && request()->has('address_street')) {
            //Adding new Location to Input if all of the address field inserted
            $location = new Location();
            $location->province_id = request()->get('address_province');
            $location->city_id = request()->get('address_city');
            $location->kelurahan_id = request()->get('address_kelurahan');
            $location->kecamatan_id = request()->get('address_kecamatan');
            $location->street_name = request()->get('address_street');
            $location->zipcode = request()->get('address_zipcode');
            $location->save();

            request()->merge(array('location_id' => $location->id));
        }

        $v = Validator::make(request()->all(),
            array(
                'username' => 'required',
                'name' => 'required',
                'password' => 'between:8,25',
                'email' => 'required|email',
                'phone_number' => 'required|numeric',
                'location_id' => 'required',
                'address_zipcode' => 'numeric',
            )
        );

        if ($v->fails()) {
            Session::put('error', true);

            return Redirect::route('frontend.member.completeregistration')->with(array('errors' => $v->messages()))->withInput(request()->except('password'));
        } else {
            if ($user == null) {
                App::abort(404);
            }
            $user->fill($input);
            if (request()->hasFile('picture')) {
                if (request()->file('picture')->isValid()) {
                    $file = request()->file('picture');

                    $destinationpath = public_path().'/profilepicture';
                    $extension = 'jpg';
                    $filename = str_random(30).".{$extension}";
                    $upload_success = request()->file('picture')->move($destinationpath, $filename);
                    if ($upload_success) {
                        $user->picture = $filename;
                    }
                }
            }

            if (request()->has('password')) {
                $user->password = Hash::make(request()->get('password'));
            } else {
                $user->password = $oldPassword;
            }

            $user->save();

            Session::put('update_success', true);

            return Redirect::route('backend.home.index');
        }
    }
}
