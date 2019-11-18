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
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
use App\Models\UserBooking;
use App\Repositories\UserRepository;
use App\Repositories\UserProfileRepository;
use App\Repositories\CountryRepository;
use App\Repositories\CityRepository;
use App\Repositories\CurrencyRepository;
use App\Repositories\PriceInclusionRepository;
use App\Repositories\UserPortofolioRepository;
use App\Repositories\UserGalleryRepository;
use App\Repositories\UserPriceInclusionRepository;
use App\Repositories\UserAvailabilityAreaRepository;
use App\Repositories\UserBookingRepository;
use App\Repositories\TalentCategoryRepository;

class TalentController extends BaseController
{
    protected $userRepo;
    protected $userProfileRepo;
    protected $userPortofolioRepo;
    protected $userGalleryRepo;
    protected $priceInclusionRepo;
    protected $userPriceInclusionRepo;
    protected $countryRepo;
    protected $cityRepo;
    protected $currencyRepo;
    protected $userAreaRepo;
    protected $userBookingRepo;
    protected $talentCategoryRepo;

    public function __construct(UserRepository $_userRepo,
        UserProfileRepository $_userProfileRepo,
        UserBookingRepository $_userBookingRepo,
        UserPortofolioRepository $_userPortofolioRepo,
        UserGalleryRepository $_userGalleryRepo,
        PriceInclusionRepository $_priceInclusionRepo,
        UserPriceInclusionRepository $_userPriceInclusionRepo,
        CountryRepository $_countryRepo,
        CityRepository $_cityRepo,
        CurrencyRepository $_currencyRepo,
        UserAvailabilityAreaRepository $_userAreaRepo,
        TalentCategoryRepository $_talentCategoryRepo)
    {
        parent::__construct();
        $this->userRepo = $_userRepo;
        $this->userProfileRepo = $_userProfileRepo;
        $this->userBookingRepo = $_userBookingRepo;
        $this->userPortofolioRepo = $_userPortofolioRepo;
        $this->userGalleryRepo = $_userGalleryRepo;
        $this->priceInclusionRepo = $_priceInclusionRepo;
        $this->userPriceInclusionRepo = $_userPriceInclusionRepo;
        $this->countryRepo = $_countryRepo;
        $this->cityRepo = $_cityRepo;
        $this->currencyRepo = $_currencyRepo;
        $this->userAreaRepo = $_userAreaRepo;
        $this->talentCategoryRepo = $_talentCategoryRepo;
    }

    // PROFILE
    public function postBookingRequest(Request $request)
    {
        // Init User
        $user = auth()->user();
        $userBookingRequest = new UserBooking;
        $param = $request->all(); 
        $eventFormattedDateStart = Carbon::createFromFormat('m/d/Y', $param['event_date_start'])->toDateString();
        $eventFormattedDateEnd = Carbon::createFromFormat('m/d/Y', $param['event_date_end'])->toDateString();
        $result = $this->userBookingRepo->create(array_merge($param, [
            'user_id' => $user->id,
            'event_date_start' => $eventFormattedDateStart,
            'event_date_end' => $eventFormattedDateEnd,
            'email' => $param['email'],
            'phone_number' => $param['phone_number'],
            'status' => UserBooking::STATUS_CREATED
        ]));
        if ($result) {
            // Notify by Email
            try {
                $mailedUser = $result->talentUser;
                Mail::queue("emails.".($mailedUser->language_setting && in_array($mailedUser->language_setting, $mailedUser->getLanguageOptions()) ? $mailedUser->language_setting : config('app.fallback_locale', 'en')).".talent.bookingrequestnew", [
                    'talentName' => $mailedUser->name,
                    'userBookingRequest' => $result
                ], function ($m) use ($mailedUser) {
                    $m->to($mailedUser->email, $mailedUser->name)->subject("Talentsaga | SOMEONE IS REQUESTING TO BOOK YOUR SERVICE");
                });
            } catch (Exception $e) { }
            // Redirect Back with Message
            $this->notifySuccess(trans('notification.bookingrequestsaved'));
            return redirect()->back();
        }
        $this->notifyError(trans('notification.bookingrequestnotsaved'));
        return redirect()->back();
    }

    public function postBookingUpdate($bookingId, Request $request)
    {
        // Init User
        $param = $request->all();
        $isApproved = isset($param['approved']) ? $param['approved'] : false;
        $isRejected = isset($param['rejected']) ? $param['rejected'] : false;
        $isCanceled = isset($param['canceled']) ? $param['canceled'] : false;
        $isDone = isset($param['done']) ? $param['done'] : false;

        $user = auth()->user();
        $userBookingRequest = $this->userBookingRepo->get($bookingId)['object'];
        if (!$userBookingRequest) {
            $this->notifyError(trans('notification.bookingnotfound'));
            return redirect()->back();
        }

        $isJobRequest = ($userBookingRequest->talent_user_id == $user->id);
        $result = $this->userBookingRepo->update($bookingId, [
            'status' => ($isApproved && $isJobRequest && in_array($userBookingRequest->status, [UserBooking::STATUS_CREATED]) ? UserBooking::STATUS_APPROVED : ($isRejected && $isJobRequest && in_array($userBookingRequest->status, [UserBooking::STATUS_CREATED]) ? UserBooking::STATUS_REJECTED : ($isCanceled && !$isJobRequest && in_array($userBookingRequest->status, [UserBooking::STATUS_CREATED]) ? UserBooking::STATUS_CANCELED : ($isDone && $isJobRequest && in_array($userBookingRequest->status, [UserBooking::STATUS_APPROVED]) ? UserBooking::STATUS_DONE : UserBooking::STATUS_CREATED) ) ) )
        ]);
        if ($result) {
            // Notify by Email
            $forTalentMailTemplate = "";
            $forTalentEmailSubject = "";
            $forTalentSeekerMailTemplate = "";
            $forTalentSeekerEmailSubject = "";
            if ($isApproved) {
                $forTalentSeekerMailTemplate = 'emails.#.talentseeker.bookingrequestaccepted';
                $forTalentSeekerEmailSubject = "Talentsaga | YOUR BOOKING REQUEST IS ACCEPTED";
            } elseif ($isRejected) {
                $forTalentSeekerMailTemplate = 'emails.#.talentseeker.bookingrequestrejected';
                $forTalentSeekerEmailSubject = "Talentsaga | YOUR BOOKING REQUEST IS REJECTED";
            } elseif ($isCanceled) {
                $forTalentMailTemplate = 'emails.#.talent.bookingrequestcanceled';
                $forTalentEmailSubject = "Talentsaga | THE BOOKING REQUEST IS CANCELLED";
                $forTalentSeekerMailTemplate = 'emails.#.talentseeker.bookingrequestcanceled';
                $forTalentSeekerEmailSubject = "Talentsaga | THE TALENT HAS CANCELLED YOUR BOOKING";
            } elseif ($isDone) {
                $forTalentSeekerMailTemplate = 'emails.#.talentseeker.bookingrequestdone';
                $forTalentSeekerEmailSubject = "Talentsaga | REVIEW YOUR TALENT";
            }
            if (!empty($forTalentMailTemplate)) {
                try {
                    $mailedUser = $userBookingRequest->talentUser;
                    Mail::queue(str_replace("#", ($mailedUser->language_setting && in_array($mailedUser->language_setting, $mailedUser->getLanguageOptions()) ? $mailedUser->language_setting : config('app.fallback_locale', 'en')), $forTalentMailTemplate), [
                        'talentName' => $mailedUser->name,
                        'userBookingRequest' => $userBookingRequest
                    ], function ($m) use ($mailedUser, $forTalentEmailSubject) {
                        $m->to($mailedUser->email, $mailedUser->name)->subject($forTalentEmailSubject);
                    });
                } catch (Exception $e) { }
            }
            if (!empty($forTalentSeekerMailTemplate)) { 
                try {
                    $mailedUser = $userBookingRequest->user;
                    Mail::queue(str_replace("#", ($mailedUser->language_setting && in_array($mailedUser->language_setting, $mailedUser->getLanguageOptions()) ? $mailedUser->language_setting : config('app.fallback_locale', 'en')), $forTalentSeekerMailTemplate), [
                        'talentSeekerName' => $mailedUser->name,
                        'userBookingRequest' => $userBookingRequest
                    ], function ($m) use ($mailedUser, $forTalentSeekerEmailSubject) {
                        $m->to($mailedUser->email, $mailedUser->name)->subject($forTalentSeekerEmailSubject);
                    });
                } catch (Exception $e) { }
            }
            // Redirect Back with Message
            $this->notifySuccess(trans('notification.bookingnotfound'));
            if ($isApproved && $isJobRequest && in_array($userBookingRequest->status, [UserBooking::STATUS_CREATED])) {
                $this->notifySuccess(trans('notification.bookingrequestapproved'));
            } elseif ($isRejected && $isJobRequest && in_array($userBookingRequest->status, [UserBooking::STATUS_CREATED])) { 
                $this->notifySuccess(trans('notification.bookingrequestrejected'));
            } elseif ($isCanceled && !$isJobRequest && in_array($userBookingRequest->status, [UserBooking::STATUS_CREATED])) { 
                $this->notifySuccess(trans('notification.bookingrequestcanceled'));
            } elseif ($isDone && $isJobRequest && in_array($userBookingRequest->status, [UserBooking::STATUS_APPROVED])) { 
                $this->notifySuccess(trans('notification.bookingrequestdone'));
            } else {
                $this->notifyMessage(trans('notification.bookingrequeststatusnotupdated'));
            }
            return redirect()->back();
        }
        $this->notifyError(trans('notification.bookingrequeststatusnotupdated'));
        return redirect()->back();
    }

    public function postBookingReview($bookingId, Request $request)
    {
        // Init User
        $param = $request->all();
        $user = auth()->user();
        $userBookingRequest = $this->userBookingRepo->get($bookingId)['object'];
        if (!$userBookingRequest || ($userBookingRequest->user_id != $user->id)) {
            $this->notifyError(trans('notification.bookingnotfound'));
            return redirect()->back();
        }

        $param['talent_review_date'] = Carbon::now()->toDateTimeString();
        // dd(array_only($param, ['talent_review_date', 'talent_rate', 'talent_review']));
        $result = $this->userBookingRepo->update($bookingId, array_only($param, ['talent_review_date', 'talent_rate', 'talent_review']));
        if ($result) {
            $this->notifySuccess(trans('notification.bookingreviewsaved'));
            return redirect()->back();
        }
        $this->notifyError(trans('notification.bookingreviewnotsaved'));
        return redirect()->back();
    }

    public function getBookingList(Request $request)
    {
        // Init User
        $user = auth()->user();
        $userBookingRequest = new UserBooking;
        $param = $request->all();
        $bookingList = $this->userBookingRepo->getByParameter([
            'orderBy' => (isset($param['order']) && ($param['order'] == 'newestevent' || $param['order'] == 'oldestevent') ? 'event_date_start' : 'created_at'),
            'orderType' => (isset($param['order']) && ($param['order'] == 'oldest' || $param['order'] == 'oldestevent') ? 'asc' : 'desc'),
            'user_id' => $user->id,
            'perPage' => 10,
            'paginate' => true
        ]);
        return view('frontend.talent.bookinglist', compact('user', 'userProfile', 'bookingList'));
    }

    public function getRequestList(Request $request)
    {
        // Init User
        $user = auth()->user();
        $userBookingRequest = new UserBooking;
        $param = $request->all();
        $requestList = $this->userBookingRepo->getByParameter([
            'orderBy' => (isset($param['order']) && ($param['order'] == 'newestevent' || $param['order'] == 'oldestevent') ? 'event_date_start' : 'created_at'),
            'orderType' => (isset($param['order']) && ($param['order'] == 'oldest' || $param['order'] == 'oldestevent') ? 'asc' : 'desc'),
            'talent_user_id' => $user->id,
            'perPage' => 10,
            'paginate' => true
        ]);
        return view('frontend.talent.offerlist', compact('user', 'userProfile', 'requestList'));
    }

    public function getTalentRawList(Request $request) {
        $param = $request->all();
        if (!isset($param['category_id']) || !isset($param['city_id'])) {
            return App::abort(404);
        }
        $category = $this->talentCategoryRepo->get($param['category_id'])['object'];
        $city = $this->cityRepo->get($param['city_id'])['object'];
        if (!$category || !$city) {
            return App::abort(404);
        }
        return redirect()->route('talent.list', [
            'categorySlug' => $category->slug,
            'country_ids' => ($city->country ? $city->country->id : ''),
            'city_ids' => $city->id
        ]);
    }

    public function getTalentList($categorySlug, Request $request)
    {
        // Init User
        $talentCategory = $this->talentCategoryRepo->getByParameter([
            'slug' => $categorySlug,
            'perPage' => 1,
            'paginate' => false
        ]);
        if (!$talentCategory) {
            return App::abort(404);
        }
        $param = $request->only(['similar_name', '_talentExpertises_id', 'country_ids', 'city_ids', '_min_price_estimation', '_max_price_estimation']);
        $keywordTerms = '';
        if (isset($param['keyword']) && !empty(trim($param['keyword']))) {
            $keywordTerms = trim($param['keyword']);
            unset($param['keyword']);
        }
        $param['_talentExpertises_id'] = isset($param['_talentExpertises_id']) && is_array($param['_talentExpertises_id']) ? implode(',', array_keys($param['_talentExpertises_id'])) : null;
        $cityIds = isset($param['city_ids']) && !empty($param['city_ids']) ? [ $param['city_ids'] ] : [];
        //dd($param);
        $talentList = $this->userProfileRepo->getByParameter(array_merge($param, [
            'talent_category_id' => $talentCategory->id,
            'perPage' => 12,
            'paginate' => true
        ]), [
            'optional_dependency' => ['user'],
            'extended_condition' => function($query) use($cityIds, $keywordTerms) {
                $query->where('id', '>', 0);
                $query->whereHas('user', function($q) {
                    $q->where('id', '>', 0);
                });
                if (is_array($cityIds) && count($cityIds) > 0) {
                    $query->where(function($deepQuery) use($cityIds) {
                        $deepQuery->whereHas('user', function($quser) use($cityIds) {
                            $quser->whereHas('userAvailabilityAreas', function($qarea) use($cityIds) {
                                $qarea->whereIn('city_id', $cityIds);
                            });
                        });
                        $deepQuery->orWhereIn('city_id', $cityIds);
                    });
                }
                if (!empty($keywordTerms)) {
                    $query->simpleSearch($keywordTerms);
                }
            }
        ]);
        $expertises = $talentCategory->curatedExpertises;
        return view('frontend.talent.list', compact('param', 'talentCategory', 'talentList', 'expertises', 'keywordTerms'));
    }

    public function getTalentSearch(Request $request)
    {
        // Init User
        $param = $request->only(['keyword', 'talent_category_id', '_talentExpertises_id', 'country_ids', 'city_ids', '_min_price_estimation', '_max_price_estimation']);
        $keywordTerms = '';
        if (isset($param['keyword']) && !empty(trim($param['keyword']))) {
            $keywordTerms = trim($param['keyword']);
            unset($param['keyword']);
        }
        $param['_talentExpertises_id'] = isset($param['_talentExpertises_id']) && is_array($param['_talentExpertises_id']) ? implode(',', array_keys($param['_talentExpertises_id'])) : null;
        $cityIds = isset($param['city_ids']) && !empty($param['city_ids']) ? [ $param['city_ids'] ] : [];
        
        $talentCategories = $this->talentCategoryRepo->getByParameter([
            'perPage' => TalentCategoryRepository::FETCH_ALL,
            'paginate' => false
        ]);

        $talentList = $this->userProfileRepo->getByParameter(array_merge($param, [
            'perPage' => 12,
            'paginate' => true
        ]), [
            'optional_dependency' => ['user'],
            'extended_condition' => function($query) use($cityIds, $keywordTerms) {
                $query->where('id', '>', 0);
                $query->whereHas('user', function($q) {
                    $q->where('id', '>', 0);
                });
                if (is_array($cityIds) && count($cityIds) > 0) {
                    $query->where(function($deepQuery) use($cityIds) {
                        $deepQuery->whereHas('user', function($quser) use($cityIds) {
                            $quser->whereHas('userAvailabilityAreas', function($qarea) use($cityIds) {
                                $qarea->whereIn('city_id', $cityIds);
                            });
                        });
                        $deepQuery->orWhereIn('city_id', $cityIds);
                    });
                }
                if (!empty($keywordTerms)) {
                    $query->simpleSearch($keywordTerms);
                }
            }
        ]);
        return view('frontend.talent.search', compact('param', 'keywordTerms', 'talentList', 'talentCategories'));
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
        $passwordChanged = false;
        if ($request->hasFile('picture')) {
            $param = $request->only(['picture']); 
            $pictureUpdated = $this->userRepo->update($user->id, $param, $user);
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
        if ($pictureUpdated || $passwordChanged) {
            $this->notifySuccess(trans('notification.accountdataupdated'));
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
        return view('frontend.user.dashboard.personal', compact('user', 'userProfile'));
    }

    public function postDashboardPersonal(Request $request)
    {
        // Init User
        $user = auth()->user();
        // Request Parameter
        $userProfileData = $request->all();
        $isDraft = isset($userProfileData['saveasdraft']) ? $userProfileData['saveasdraft'] : false;
        $firstCat = TalentCategory::first();
        if (!$firstCat) 
            $firstCat = TalentCategory::create([
                'name' => 'Other Category'
            ]);
        $fistExpertise = TalentExpertise::first();
        if (!$fistExpertise) 
            $fistExpertise = TalentExpertise::create([
                'talent_category_id' => $firstCat->id,
                'name' => 'Other Expertise',
                'is_curated' => false
            ]);
        $userProfileData = array_merge($userProfileData, [
            'user_id' => $user->id,
            'talent_category_id' => $firstCat->id,
            'talent_profession' => 'Any Profession',
            'talent_expertise_id' => $fistExpertise->id,
            'price_notes' => 'Contact For Price',
            'contact_for_price' => true,
            'status' => ($isDraft ? UserProfile::STATUS_DRAFT : UserProfile::STATUS_PUBLISHED)
        ]);
        if (isset($userProfileData['date']) &&
            isset($userProfileData['month']) &&
            isset($userProfileData['year']))
            $userProfileData['birthdate'] = $userProfileData['year'].'-'.str_pad($userProfileData['month'], 2, "0", STR_PAD_LEFT).'-'.str_pad($userProfileData['date'], 2, "0", STR_PAD_LEFT);
        if (isset($userProfileData['country_id']))
            $userProfileData['country_id'] = str_replace('number:', '', $userProfileData['country_id']);
        if (isset($userProfileData['city_id']))
            $userProfileData['city_id'] = str_replace('number:', '', $userProfileData['city_id']);
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
        $userProfileExpertises = $userProfile->talentExpertises();
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
        $userPortofolios = $this->userPortofolioRepo->getByParameter([
                            'user_id' => $user->id,
                            'perPage' => UserPortofolioRepository::FETCH_ALL,
                            'paginate' => false
                        ]);
        return view('frontend.user.dashboard.portofolio', compact('user', 'userProfile', 'userPortofolios'));
    }

    public function postDashboardPortofolio(Request $request) {
        $user = auth()->user();
        $userProfile = $user->profile;
        if (!$userProfile) {
            $this->notifyMessage(trans('notification.completepersonaldatafirst'));
            return redirect()->route('user.dashboard.personal');
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
            return redirect()->back();
        }
        $this->notifyError(trans('notification.portofolionotupdated'));
        return redirect()->back();
    }

    public function getDashboardGallery() {
        $user = auth()->user();
        $userProfile = $user->profile;
        if (!$userProfile) {
            $this->notifyMessage(trans('notification.completepersonaldatafirst'));
            return redirect()->route('user.dashboard.personal');
        }
        $userGalleries = $this->userGalleryRepo->getByParameter([
                            'user_id' => $user->id,
                            'perPage' => UserGalleryRepository::FETCH_ALL,
                            'paginate' => false
                        ]);
        return view('frontend.user.dashboard.gallery', compact('user', 'userProfile', 'userGalleries'));
    }

    public function postDashboardGallery(Request $request) {
        $user = auth()->user();
        $userProfile = $user->profile;
        if (!$userProfile) {
            $this->notifyMessage(trans('notification.completepersonaldatafirst'));
            return redirect()->route('user.dashboard.personal');
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
        if ($nbSaved) {
            $this->notifySuccess(trans('notification.galleryupdated', ['nbsaved' => $nbSaved]));
            return redirect()->back();
        }
        $this->notifyError(trans('notification.gallerynotupdated'));
        return redirect()->back();
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
            $availabilityAreaSet[$city->country_id] = (isset($availabilityAreaSet[$city->country_id]) ? $availabilityAreaSet[$city->country_id].',' : '') . $city->name;
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
        $availabilityAreaIds = $availabilityAreas->pluck('city_id')->toArray();
        foreach ($availabilityAreaIds as $key => $value) {
            $availabilityAreaIds[$key] = "" . $value;
        }
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
}
