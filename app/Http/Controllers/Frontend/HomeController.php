<?php

namespace App\Http\Controllers\Frontend;

use App;
use DB;
use Input;
use Response;
use App\Repositories\ContentRepository;
use App\Repositories\FaqCategoryRepository;
use App\Repositories\FaqRepository;
use App\Repositories\VacancyRepository;
use App\Repositories\UserRepository;
use App\Repositories\ContactMessageRepository;
use App\Repositories\UserStoryRepository;
use App\Models\ContactMessage;
use App\Models\FaqCategory;
use App\Models\Faq;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserBooking;
use App\Models\UserStory;

class HomeController extends BaseController
{
    protected $contentRepo;
    protected $userRepo;
    protected $contactMessageRepo;
    protected $faqRepo;
    protected $faqCategoryRepo;
    protected $userStoryRepo;

    public function __construct(ContentRepository $_contentRepo, UserRepository $_userRepo, ContactMessageRepository $_contactMessageRepo,
        FaqRepository $_faqRepo,
        FaqCategoryRepository $_faqCategoryRepo,
        UserStoryRepository $_userStoryRepo)
    {
        $this->contentRepo = $_contentRepo;
        $this->userRepo = $_userRepo;
        $this->contactMessageRepo = $_contactMessageRepo;
        $this->faqRepo = $_faqRepo;
        $this->faqCategoryRepo = $_faqCategoryRepo;
        $this->userStoryRepo = $_userStoryRepo;
    }

    /**
     * Show Frontend / Desktop Site Homepage.
     *
     * @return View
     **/
    public function getIndex()
    {
        $showLanding = settings('enable_landing', '');
        if (!empty($showLanding)) {
            view()->share(['pageTitle' => '']);
            if (session()->has('errors')) {
                $errors = session()->get('errors');  
                if ($errors->first('email')) {
                    session()->put('error', $errors->first('email'));
                }
            }
            return view('frontend.home.landing');
        }

        $favoriteTalent = UserProfile::with(['user'])->leftJoin(DB::raw("(SELECT user_bookings.talent_user_id, avg(talent_rate) AS booking_rating FROM user_bookings WHERE talent_rate > 0  GROUP BY talent_user_id) AS booking_statistic"), 'booking_statistic.talent_user_id', '=', 'user_profiles.user_id')->orderBy(DB::raw('booking_statistic.booking_rating'), 'desc')->take(8)->get();

        $userStoryExist = $this->userStoryRepo->getByParameter([
            'perPage' => 1,
            'paginate' => false
        ]);

        view()->share(['pageTitle' => 'Home']);
        return view('frontend.home.index', compact('favoriteTalent', 'userStoryExist'));
    }

    public function getLandingPage()
    {
        view()->share(['pageTitle' => '']);

        return view('frontend.home.landing');
    }

    public function getUserStoriesJson() {
        $result['success_stories'] = [];
        $userStories = $this->userStoryRepo->getByParameter([
            'perPage' => 5,
            'paginate' => false
        ]);
        foreach ($userStories as $key => $userStory) {
            if ($userStory->user) {
                $result['success_stories'][] = [
                    "name" => $userStory->user->full_name,
                    "shortDesc" => $userStory->title,
                    "longDesc" => $userStory->highlight,
                    "imageThumb" => ($userStory->cover_image ? $userStory->cover_image_small_cover : asset('frontend/assets/img/success-story-profile-1.jpg')),
                    "imageBackground" => ($userStory->cover_image ? $userStory->cover_image_large_cover : asset('frontend/assets/img/success-story-profile-1.jpg'))
                ];
            }
        }

        return Response::json($result);
    }

    public function getUserStories() {
        $result['success_stories'] = [];
        $userStories = $this->userStoryRepo->getByParameter([
            'perPage' => 8,
            'paginate' => true
        ]);
        view()->share(['pageTitle' => 'Home']);
        return view('frontend.userstory.list', compact('userStories'));
    }

    public function getUserStoryDetail($id) {
        $result['success_stories'] = [];
        $userStory = $this->userStoryRepo->get($id)['object'];
        if (!$userStory) {
            return App::abort(404);
        }
        view()->share(['pageTitle' => 'Home']);
        return view('frontend.userstory.detail', compact('userStory'));
    }

     /**
     * Show Frequently Ask Questions (F.A.Q) Pages
     *
     * @return View
     **/
    public function getFaq($slug = null)
    {
        $faqs = $this->faqRepo->getCachedList(null);
        view()->share(['pageTitle' => 'FAQ' ]);
        return view('frontend.home.faq', compact('faqs'));
    }

    /**
     * Show Contact Us Pages
     *
     * @return View
     **/
    public function getContactform()
    {
        $contactmessage = new ContactMessage;
        $contactcategory = $contactmessage->getCategoryOptions();
        view()->share(['pageTitle' => trans('label.contactus.contactus')]);
        return view('frontend.home.contact')->with('contactmessage', $contactmessage)
                    ->with('contactcategory', $contactcategory);
    }

    /**
     * Set accept-cookie session acknowledgement
     *
     * @return View
     **/
    public function getAcceptCookie() {
        session()->put('accept-cookie', true);
        return redirect()->back();
    }

    /**
     * Process messages entry from Contact Us Pages
     *
     * @return View
     **/
    public function postContactform()
    {
        $param = Input::all();
        $param['status'] = ContactMessage::MESSAGE_CREATED;
        $contactMessage = new ContactMessage;
        $result = $this->contactMessageRepo->create($param, $contactMessage);
        // Return
        if ($result) {
            // notify success
            $this->notifySuccess(trans('notification.contactmessagesent'));
            return redirect()->route('frontend.home.contactus');
        }
        // notify error
        $this->notifyError(trans('notification.contactmessagenotsent'));
        return redirect()->route('frontend.home.contactus')->with('errors', $contactMessage->errors)->withInput($param);
    }
}
