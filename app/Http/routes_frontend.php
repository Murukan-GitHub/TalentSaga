<?php

use Illuminate\Http\Request;

Route::group(['middleware' => 'web'], function(){

    // FRONT END ROUTES
    //App::missing(function($exception)
    //{
    //    return Response::view('frontend.404', array(), 404);
    //});

    // HOME CASE
    // -- Index
    Route::get('/', ['as' => 'frontend.home', 'uses' => 'Frontend\HomeController@getIndex']);

    // -- Content & FAQ
    Route::get('/content/{slug}', ['as'=>'frontend.home.content', 'uses'=>'Frontend\ContentController@getStaticContent']);
    Route::get('/blog/{slug?}', ['as'=>'frontend.home.content.blog', 'uses'=>'Frontend\ContentController@getBlogs']);
    Route::get('/aboutus', ['as' => 'frontend.home.aboutus', 'uses' => 'Frontend\ContentController@getAboutUs']);
    Route::get('/terms', ['as' => 'frontend.home.terms', 'uses' => 'Frontend\ContentController@getTerms']);
    Route::get('/privacy', ['as' => 'frontend.home.privacy', 'uses' => 'Frontend\ContentController@getPolicy']);

    // -- Contact Us
    Route::get('/contactus', ['as' => 'frontend.home.contactus', 'uses' => 'Frontend\HomeController@getContactform']);
    Route::post('/contactus', ['as' => 'frontend.home.contactus.save', 'uses' => 'Frontend\HomeController@postContactform']);

    // -- FAQ
    Route::get('/faq/{slug?}', ['as' => 'frontend.home.faq', 'uses' => 'Frontend\HomeController@getFaq']);

    // -- Accept Cookie 
    Route::get('/acceptcookie', ['as' => 'frontend.home.acceptcookie', 'uses' => 'Frontend\HomeController@getAcceptCookie']);

    // USER CASE
    Route::group(['middleware' => 'guest'], function () {
        Route::get('register', ['as' => 'frontend.user.registration', 'uses' => 'Frontend\UserController@getRegistration']);
        Route::post('register', ['as' => 'frontend.user.registration.save', 'uses' => 'Frontend\UserController@postRegistration']);
        Route::get('/forgetpassword', ['as' => 'frontend.user.forgetpassword', 'uses' => 'Frontend\UserController@getForgetPassword']);
        Route::post('/forgetpassword', ['as' => 'frontend.user.forgetpassword.send', 'uses' => 'Frontend\UserController@postForgetPassword']);
        Route::get('confirmresetpassword/{token?}', ['as' => 'frontend.user.forgetpassword.confirm', 'uses' => 'Frontend\UserController@getConfirmResetPassword']);
        Route::post('confirmresetpassword/{token?}', ['as' => 'frontend.user.forgetpassword.confirm.submit', 'uses' => 'Frontend\UserController@postConfirmResetPassword']);
        Route::get('activateuser/{id}/{hashcode}', ['as' => 'frontend.user.activation', 'uses' => 'Frontend\UserController@activateAccount']);
        Route::get('activationsuccess', ['as' => 'frontend.user.activation.success', 'uses' => 'Frontend\UserController@activateAccountSuccess']);
        Route::get('activationfailed', ['as' => 'frontend.user.activation.failed', 'uses' => 'Frontend\UserController@activateAccountFailed']);
        Route::get('producehashcode/{id}', ['as' => 'frontend.user.hashcode', 'uses' => 'Frontend\UserController@produceHashCode']);
        Route::get('reactivation',['as'=>'frontend.reactivation.link','uses'=>'Frontend\UserController@getUserActivation']);
    });

    // AUTHENTICATED
    Route::group(['middleware' => ['web','auth']], function () {
        // User Pofile
        Route::get('/myprofile', ['as'=>'user.myprofile',  'uses' =>'Frontend\UserController@getMyProfile']);
        Route::get('/profile/{userId}', ['as'=>'user.profile',  'uses' =>'Frontend\UserController@getProfile']);

        // Become an Artist
        Route::get('/onboarding/personal', ['as'=>'user.onboarding.personal',  'uses' =>'Frontend\UserController@getOnboardingPersonal']);
        Route::post('/onboarding/personal', ['as'=>'user.onboarding.personal.save',  'uses' =>'Frontend\UserController@postOnboardingPersonal']);
        Route::get('/onboarding/talent', ['as'=>'user.onboarding.talent',  'uses' =>'Frontend\UserController@getOnboardingTalent']);
        Route::post('/onboarding/talent', ['as'=>'user.onboarding.talent.save',  'uses' =>'Frontend\UserController@postOnboardingTalent']);
        Route::get('/onboarding/portofolio', ['as'=>'user.onboarding.portofolio',  'uses' =>'Frontend\UserController@getOnboardingPortofolio']);
        Route::post('/onboarding/portofolio', ['as'=>'user.onboarding.portofolio.save',  'uses' =>'Frontend\UserController@postOnboardingPortofolio']);
        Route::get('/onboarding/gallery', ['as'=>'user.onboarding.gallery',  'uses' =>'Frontend\UserController@getOnboardingGallery']);
        Route::post('/onboarding/gallery', ['as'=>'user.onboarding.gallery.save',  'uses' =>'Frontend\UserController@postOnboardingGallery']);
        Route::get('/onboarding/pricing', ['as'=>'user.onboarding.pricing',  'uses' =>'Frontend\UserController@getOnboardingPricing']);
        Route::post('/onboarding/pricing', ['as'=>'user.onboarding.pricing.save',  'uses' =>'Frontend\UserController@postOnboardingPricing']);

        // User Dashboard
        Route::get('/dashboard/account', ['as'=>'user.dashboard.account',  'uses' =>'Frontend\UserController@getAccountInformation']);
        Route::post('/dashboard/account', ['as'=>'user.dashboard.account.save',  'uses' =>'Frontend\UserController@postAccountInformation']);
        Route::get('/dashboard/personal', ['as'=>'user.dashboard.personal',  'uses' =>'Frontend\UserController@getDashboardPersonal']);
        Route::post('/dashboard/personal', ['as'=>'user.dashboard.personal.save',  'uses' =>'Frontend\UserController@postDashboardPersonal']);
        Route::get('/dashboard/talent', ['as'=>'user.dashboard.talent',  'uses' =>'Frontend\UserController@getDashboardTalent']);
        Route::post('/dashboard/talent', ['as'=>'user.dashboard.talent.save',  'uses' =>'Frontend\UserController@postDashboardTalent']);
        Route::get('/dashboard/portofolio', ['as'=>'user.dashboard.portofolio',  'uses' =>'Frontend\UserController@getDashboardPortofolio']);
        Route::post('/dashboard/portofolio', ['as'=>'user.dashboard.portofolio.save',  'uses' =>'Frontend\UserController@postDashboardPortofolio']);
        Route::get('/dashboard/portofolio/{id}', ['as'=>'user.dashboard.portofolio.edit',  'uses' =>'Frontend\UserController@getDashboardPortofolioEdit']);
        Route::post('/dashboard/portofolio/{id}/save', ['as'=>'user.dashboard.portofolio.edit.save',  'uses' =>'Frontend\UserController@postDashboardPortofolioEdit']);
        Route::post('/dashboard/portofolio/{id}/delete', ['as'=>'user.dashboard.portofolio.delete',  'uses' =>'Frontend\UserController@postDashboardPortofolioDelete']);
        Route::get('/dashboard/gallery', ['as'=>'user.dashboard.gallery',  'uses' =>'Frontend\UserController@getDashboardGallery']);
        Route::post('/dashboard/gallery', ['as'=>'user.dashboard.gallery.save',  'uses' =>'Frontend\UserController@postDashboardGallery']);
        Route::get('/dashboard/gallery/{id}/edit', ['as'=>'user.dashboard.gallery.edit',  'uses' =>'Frontend\UserController@getDashboardGalleryEdit']);
        Route::post('/dashboard/gallery/{id}/edit', ['as'=>'user.dashboard.gallery.edit.save',  'uses' =>'Frontend\UserController@postDashboardGalleryEdit']);
        Route::post('/dashboard/gallery/{id}/delete', ['as'=>'user.dashboard.gallery.delete',  'uses' =>'Frontend\UserController@postDashboardGalleryDelete']);
        Route::get('/dashboard/pricing', ['as'=>'user.dashboard.pricing',  'uses' =>'Frontend\UserController@getDashboardPricing']);
        Route::post('/dashboard/pricing', ['as'=>'user.dashboard.pricing.save',  'uses' =>'Frontend\UserController@postDashboardPricing']);

        Route::post('/booking/create', ['as'=>'user.booking.create.save', 'uses' => 'Frontend\TalentController@postBookingRequest']);
        Route::post('/bookingupdate/{bookingId}', ['as'=>'user.booking.update.save', 'uses' => 'Frontend\TalentController@postBookingUpdate']);
        Route::post('/bookingreview/{bookingId}', ['as'=>'user.booking.review.save', 'uses' => 'Frontend\TalentController@postBookingReview']);
        Route::get('/booking/list', ['as'=>'user.booking.list',  'uses' =>'Frontend\TalentController@getBookingList']);
        Route::get('/booking/request', ['as'=>'user.booking.request',  'uses' =>'Frontend\TalentController@getRequestList']);
    });

    // List Talent
    Route::get('/talent/list', ['as'=>'talent.list.raw',  'uses' =>'Frontend\TalentController@getTalentRawList']);
    Route::get('/talent/list/{categorySlug}', ['as'=>'talent.list',  'uses' =>'Frontend\TalentController@getTalentList']);
    Route::get('/talent/search', ['as'=>'talent.search',  'uses' =>'Frontend\TalentController@getTalentSearch']);

    // User Story 
    Route::get('/userstoryjson', ['as' => 'user.story.json', 'uses' => 'Frontend\HomeController@getUserStoriesJson']);
    Route::get('/userstory', ['as' => 'user.story.list', 'uses' => 'Frontend\HomeController@getUserStories']);
    Route::get('/userstory/{id}', ['as' => 'user.story.detail', 'uses' => 'Frontend\HomeController@getUserStoryDetail']);

    // -- Newsletter Subscribe
    Route::post('/newslettersubscribe', ['as' => 'frontend.newsletter.subscribe', 'uses' => 'Frontend\NewsletterController@updateSubscriber']);

    // -- Location API
    Route::get('locations', [
        'as' => 'frontend.location.fetcher',
        function () {
            $selectedCountryId = request()->get('selected_country_id', null); 
            if ($selectedCountryId == "?") $selectedCountryId = [];
            $selectedCountryId = !empty($selectedCountryId) ? explode(',', $selectedCountryId) : [];
            $selectedCityId = request()->get('selected_city_id', null);
            if ($selectedCityId == "?") $selectedCityId = [];
            $selectedCityId = !empty($selectedCityId) ? explode(',', $selectedCityId) : [];
            $countries = App\Models\Country::active()->orderBy('name')->get();
            $result[] = [
                'name' => '-- select country --',
                'value' => '',
                'selected' => empty($selectedCountryId),
                'cities' => [
                    [
                        'name' => '-- select city --',
                        'value' => '',
                        'selected' => empty($selectedCityId)
                    ]
                ]
            ];
            foreach ($countries as $country) {
                $subResult = [
                    'name' => $country->name,
                    'value' => $country->id,
                    'selected' => in_array($country->id, $selectedCountryId)
                ];
                /*
                $subResult['cities'][] = [
                    'name' => '-- select city --',
                    'value' => '',
                    'selected' => false
                ];
                */
                foreach ($country->cities as $city) {
                    if ($city->is_active) {
                        $subResult['cities'][] = [
                            'name' => $city->name,
                            'value' => $city->id,
                            'selected' => in_array($city->id, $selectedCityId)
                        ];
                    }
                }
                $result[] = $subResult;
            }
            return Response::json($result);
        }
    ]);

    // -- Talent & Expertise API
    Route::get('talentexpertise', [
        'as' => 'frontend.talentexpertise.fetcher',
        function () {
            $selectedCategoryId = request()->get('selected_category_id', null); 
            $selectedCategoryId = $selectedCategoryId ? explode(',', $selectedCategoryId) : [];
            $selectedExpertiseId = request()->get('selected_expertise_id', null);
            $selectedExpertiseId = $selectedExpertiseId ? explode(',', $selectedExpertiseId) : [];
            $categories = getTalentCategories();
            $result = [];
            foreach ($categories as $category) {
                $subResult = [
                    'description' => $category->name__trans,
                    'value' => $category->id,
                    'selected' => in_array($category->id, $selectedCategoryId)
                ];
                $subResult['expertise'] = [];
                foreach ($category->expertises as $expertise) {
                    if ($expertise->is_curated) {
                        $subResult['expertise'][] = [
                            'description' => $expertise->name__trans,
                            'value' => $expertise->id,
                            'selected' => in_array($expertise->id, $selectedExpertiseId)
                        ];
                    }
                }
                $result[] = $subResult;
            }
            return Response::json($result);
        }
    ]);

    // -- Profile Shortcut
    Route::get('{user}', [
        'as' => 'frontend.user.profile.shortcut',
        function (App\Models\User $user) {
            // Process...
            if (!$user) return App::abort(404);
            $userController = app()->make('App\Http\Controllers\Frontend\UserController');
            return $userController->getProfile($user->id);
        }
    ]);
});
