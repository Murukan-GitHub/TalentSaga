<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


// Domain Definition
$domain = '';
switch (App::environment()) {
    case 'local':
        $domain = last(explode('//', env('SITE_URL')));
        break;
    case 'staging':
        $domain = 'talentsaga.suitdev.com';
        break;
    case 'production':
    default:
        $domain = 'talentsaga.com';
        break;
}

Config::set('app.domain', $domain);

/*
  |--------------------------------------------------------------------------
  | Pattern Shortcut
  |--------------------------------------------------------------------------
  |
  | Define frequently used pattern
  |
 */

Route::pattern('id', '\d+');
Route::pattern('slug', '[a-z0-9-]+');


/*
 * Pathc Related to New Relic Issue
 */
Route::macro('after', function ($callback) {
    $this->events->listen('router.filter:after:newrelic-patch', $callback);
});

/*
 * Routes List
 */

// Scope Authentication
Route::group(['middleware' => 'web'], function () {
    // User Login
    Route::resource('sessions', 'Auth\SessionController');
    Route::get('logout', ['as'=>'sessions.logout', 'middleware' => 'auth', 'uses' =>'Auth\SessionController@destroy']);
    Route::group(['middleware' => 'guest'], function () {
        Route::get('login', ['as'=>'sessions.login', 'uses' =>'Auth\SessionController@create']);
        Route::post('guest', ['as'=>'sessions.guest', 'uses' =>'Auth\SessionController@guest']);
        Route::get('auth/{app}', ['as'=>'sessions.auth', 'uses' =>'Auth\SessionController@auth']);
        Route::get('redirect/auth/{app}', ['as'=>'sessions.redirect.auth', 'uses' =>'Auth\SessionController@redirectAuth']);
    });
});

// Scope after Admin Logged in
Route::group(['prefix' => 'emma', 'middleware' => ['web', 'admin']], function() {
    // ----- HELPER -----
    // Upload Handler 
    Route::post('uploadfile', array(
        'as' => 'admin.uploadfile',
        function(){
            try {
                $CKEditorFuncNum = Input::get("CKEditorFuncNum");
                $destinationPath = public_path() . '/files/raw/';
                $fileName = substr(str_shuffle(str_repeat('abcdefghijklmnopqrstuvwxyz0123456789', 5)), 0, 5) . '.' . Input::file('upload')->getClientOriginalExtension();
                $result = Input::file('upload')->move($destinationPath, $fileName);
                if ($result) {
                    $url = url('/files/raw/' . $fileName);
                    echo "<script>window.parent.CKEDITOR.tools.callFunction("
                        . $CKEditorFuncNum . ", \"" . $url . "\");</script>";
                }
            } catch (Exception $e) {
                echo "Upload failed!";
            }
        })
    );

    // ----- HOME DASHBOARD -----
    // Index
    Route::get('/', ['as' => 'backend.home.index', 'uses' => 'Backend\HomeController@getIndex']);
    Route::get('appuser-summary.json', ['as' => 'backend.home.appuser-summary', 'uses' => 'Backend\HomeController@getAppUserSummaryJson']);
    Route::get('pattern', ['as' => 'backend.home.pattern', 'uses' => 'Backend\HomeController@pattern']);

    // ----- BACKEND -----
    // Content Type Management
    Route::group(['prefix' => 'contenttype', 'before' => 'admin'], function() {
        Route::get('index', ['as' => 'backend.contenttype.index', 'uses' => 'Backend\ContentTypeController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.contenttype.index.json', 'uses' => 'Backend\ContentTypeController@postIndexJson']);
        Route::get('create', ['as' => 'backend.contenttype.create', 'uses' => 'Backend\ContentTypeController@getCreate']);
        Route::post('create', ['as' => 'backend.contenttype.store', 'uses' => 'Backend\ContentTypeController@postCreate']);
        Route::get('{id}/update', ['as' => 'backend.contenttype.edit', 'uses' => 'Backend\ContentTypeController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.contenttype.update', 'uses' => 'Backend\ContentTypeController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.contenttype.destroy', 'uses' => 'Backend\ContentTypeController@postDelete']);
    });

    // Content Category Management
    Route::group(['prefix' => 'contentcategory', 'before' => 'admin'], function() {
        Route::get('index', ['as' => 'backend.contentcategory.index', 'uses' => 'Backend\ContentCategoryController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.contentcategory.index.json', 'uses' => 'Backend\ContentCategoryController@postIndexJson']);
        Route::get('create', ['as' => 'backend.contentcategory.create', 'uses' => 'Backend\ContentCategoryController@getCreate']);
        Route::post('create', ['as' => 'backend.contentcategory.store', 'uses' => 'Backend\ContentCategoryController@postCreate']);
        Route::get('{id}/update', ['as' => 'backend.contentcategory.edit', 'uses' => 'Backend\ContentCategoryController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.contentcategory.update', 'uses' => 'Backend\ContentCategoryController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.contentcategory.destroy', 'uses' => 'Backend\ContentCategoryController@postDelete']);
    });

    // Content Management
    Route::group(['prefix' => 'content', 'before' => 'admin'], function() {
        Route::get('index', ['as' => 'backend.content.index', 'uses' => 'Backend\ContentController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.content.index.json', 'uses' => 'Backend\ContentController@postIndexJson']);
        Route::get('create', ['as' => 'backend.content.create', 'uses' => 'Backend\ContentController@getCreate']);
        Route::post('create', ['as' => 'backend.content.store', 'uses' => 'Backend\ContentController@postCreate']);
        Route::get('{id}', ['as' => 'backend.content.show', 'uses' => 'Backend\ContentController@getView']);
        Route::get('{id}/update', ['as' => 'backend.content.edit', 'uses' => 'Backend\ContentController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.content.update', 'uses' => 'Backend\ContentController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.content.destroy', 'uses' => 'Backend\ContentController@postDelete']);
    });

    // Team Management
    Route::group(['prefix' => 'team', 'before' => 'admin'], function() {
        Route::get('index', ['as' => 'backend.team.index', 'uses' => 'Backend\TeamController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.team.index.json', 'uses' => 'Backend\TeamController@postIndexJson']);
        Route::get('create', ['as' => 'backend.team.create', 'uses' => 'Backend\TeamController@getCreate']);
        Route::post('create', ['as' => 'backend.team.store', 'uses' => 'Backend\TeamController@postCreate']);
        Route::get('{id}', ['as' => 'backend.team.show', 'uses' => 'Backend\TeamController@getView']);
        Route::get('{id}/update', ['as' => 'backend.team.edit', 'uses' => 'Backend\TeamController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.team.update', 'uses' => 'Backend\TeamController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.team.destroy', 'uses' => 'Backend\TeamController@postDelete']);
    });

    // Site Settings
    Route::group(['prefix' => 'settings'], function() {
        Route::get('view', ['as' => 'backend.settings.view', 'uses' => 'Backend\SettingsController@getList']);
        Route::post('save', ['as' => 'backend.settings.save', 'uses' => 'Backend\SettingsController@postSaveSettings']);
    });


    // ----- SITE USERS -----
    // User Management
    Route::group(['prefix' => 'user', 'before' => 'admin'], function() {
        Route::get('index', ['as' => 'backend.user.index', 'uses' => 'Backend\UserController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.user.index.json', 'uses' => 'Backend\UserController@postIndexJson']);
        Route::get('optionsjson', ['as' => 'backend.user.options.json', 'uses' => 'Backend\UserController@getListJson']);
        Route::get('create', ['as' => 'backend.user.create', 'uses' => 'Backend\UserController@getCreate']);
        Route::post('create', ['as' => 'backend.user.store', 'uses' => 'Backend\UserController@postCreate']);
        Route::get('{id}', ['as' => 'backend.user.show', 'uses' => 'Backend\UserController@getView']);
        Route::get('{id}/update', ['as' => 'backend.user.edit', 'uses' => 'Backend\UserController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.user.update', 'uses' => 'Backend\UserController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.user.destroy', 'uses' => 'Backend\UserController@postDelete']);
        Route::get('exportxls', ['as' => 'backend.user.exportxls', 'uses' => 'Backend\UserController@exportToExcel']);

        Route::get('updateprofile', ['as' => 'admin.user.updateprofile', 'uses' => 'Backend\UserController@updateProfile']);
        Route::post('updateprofile', ['as' => 'admin.user.updateprofile.save', 'uses' => 'Backend\UserController@postUpdateProfile']);
    });

    // User Profile Management
    Route::group(['prefix' => 'userprofile', 'before' => 'admin'], function () {
        Route::get('index', ['as' => 'backend.userprofile.index', 'uses' => 'Backend\UserProfileController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.userprofile.index.json', 'uses' => 'Backend\UserProfileController@postIndexJson']);
        Route::get('create', ['as' => 'backend.userprofile.create', 'uses' => 'Backend\UserProfileController@getCreate']);
        Route::post('create', ['as' => 'backend.userprofile.store', 'uses' => 'Backend\UserProfileController@postCreate']);
        Route::get('{id}/update', ['as' => 'backend.userprofile.edit', 'uses' => 'Backend\UserProfileController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.userprofile.update', 'uses' => 'Backend\UserProfileController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.userprofile.destroy', 'uses' => 'Backend\UserProfileController@postDelete']);
    });

    // User Price Inclusion Management
    Route::group(['prefix' => 'userpriceinclusion', 'before' => 'admin'], function () {
        Route::get('index', ['as' => 'backend.userpriceinclusion.index', 'uses' => 'Backend\UserPriceInclusionController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.userpriceinclusion.index.json', 'uses' => 'Backend\UserPriceInclusionController@postIndexJson']);
        Route::get('create', ['as' => 'backend.userpriceinclusion.create', 'uses' => 'Backend\UserPriceInclusionController@getCreate']);
        Route::post('create', ['as' => 'backend.userpriceinclusion.store', 'uses' => 'Backend\UserPriceInclusionController@postCreate']);
        Route::get('{id}/update', ['as' => 'backend.userpriceinclusion.edit', 'uses' => 'Backend\UserPriceInclusionController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.userpriceinclusion.update', 'uses' => 'Backend\UserPriceInclusionController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.userpriceinclusion.destroy', 'uses' => 'Backend\UserPriceInclusionController@postDelete']);
    });

    // User Expertises Management
    Route::group(['prefix' => 'userexpertise', 'before' => 'admin'], function () {
        Route::get('index', ['as' => 'backend.userexpertise.index', 'uses' => 'Backend\UserExpertiseController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.userexpertise.index.json', 'uses' => 'Backend\UserExpertiseController@postIndexJson']);
        Route::get('create', ['as' => 'backend.userexpertise.create', 'uses' => 'Backend\UserExpertiseController@getCreate']);
        Route::post('create', ['as' => 'backend.userexpertise.store', 'uses' => 'Backend\UserExpertiseController@postCreate']);
        //Route::get('{id}/update', ['as' => 'backend.userexpertise.edit', 'uses' => 'Backend\UserExpertiseController@getUpdate']);
        //Route::post('{id}/update', ['as' => 'backend.userexpertise.update', 'uses' => 'Backend\UserExpertiseController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.userexpertise.destroy', 'uses' => 'Backend\UserExpertiseController@postDelete']);
    });

    // User Availability Management
    Route::group(['prefix' => 'useravailabilityarea', 'before' => 'admin'], function () {
        Route::get('index', ['as' => 'backend.useravailabilityarea.index', 'uses' => 'Backend\UserAvailabilityAreaController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.useravailabilityarea.index.json', 'uses' => 'Backend\UserAvailabilityAreaController@postIndexJson']);
        Route::get('create', ['as' => 'backend.useravailabilityarea.create', 'uses' => 'Backend\UserAvailabilityAreaController@getCreate']);
        Route::post('create', ['as' => 'backend.useravailabilityarea.store', 'uses' => 'Backend\UserAvailabilityAreaController@postCreate']);
        //Route::get('{id}/update', ['as' => 'backend.useravailabilityarea.edit', 'uses' => 'Backend\UserAvailabilityAreaController@getUpdate']);
        //Route::post('{id}/update', ['as' => 'backend.useravailabilityarea.update', 'uses' => 'Backend\UserAvailabilityAreaController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.useravailabilityarea.destroy', 'uses' => 'Backend\UserAvailabilityAreaController@postDelete']);
    });

    // User Portofolio Management
    Route::group(['prefix' => 'userportofolio', 'before' => 'admin'], function () {
        Route::get('index', ['as' => 'backend.userportofolio.index', 'uses' => 'Backend\UserPortofolioController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.userportofolio.index.json', 'uses' => 'Backend\UserPortofolioController@postIndexJson']);
        Route::get('create', ['as' => 'backend.userportofolio.create', 'uses' => 'Backend\UserPortofolioController@getCreate']);
        Route::post('create', ['as' => 'backend.userportofolio.store', 'uses' => 'Backend\UserPortofolioController@postCreate']);
        Route::get('{id}/update', ['as' => 'backend.userportofolio.edit', 'uses' => 'Backend\UserPortofolioController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.userportofolio.update', 'uses' => 'Backend\UserPortofolioController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.userportofolio.destroy', 'uses' => 'Backend\UserPortofolioController@postDelete']);
    });

    // User Gallery Management
    Route::group(['prefix' => 'usergallery', 'before' => 'admin'], function () {
        Route::get('index', ['as' => 'backend.usergallery.index', 'uses' => 'Backend\UserGalleryController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.usergallery.index.json', 'uses' => 'Backend\UserGalleryController@postIndexJson']);
        Route::get('create', ['as' => 'backend.usergallery.create', 'uses' => 'Backend\UserGalleryController@getCreate']);
        Route::post('create', ['as' => 'backend.usergallery.store', 'uses' => 'Backend\UserGalleryController@postCreate']);
        Route::get('{id}/update', ['as' => 'backend.usergallery.edit', 'uses' => 'Backend\UserGalleryController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.usergallery.update', 'uses' => 'Backend\UserGalleryController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.usergallery.destroy', 'uses' => 'Backend\UserGalleryController@postDelete']);
    });

    // User Story Management
    Route::group(['prefix' => 'userstory', 'before' => 'admin'], function () {
        Route::get('index', ['as' => 'backend.userstory.index', 'uses' => 'Backend\UserStoryController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.userstory.index.json', 'uses' => 'Backend\UserStoryController@postIndexJson']);
        Route::get('create', ['as' => 'backend.userstory.create', 'uses' => 'Backend\UserStoryController@getCreate']);
        Route::post('create', ['as' => 'backend.userstory.store', 'uses' => 'Backend\UserStoryController@postCreate']);
        Route::get('{id}/update', ['as' => 'backend.userstory.edit', 'uses' => 'Backend\UserStoryController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.userstory.update', 'uses' => 'Backend\UserStoryController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.userstory.destroy', 'uses' => 'Backend\UserStoryController@postDelete']);
    });

    // ----- TRANSACTION -----
    // Talent Booking Management
    Route::group(['prefix' => 'userbooking', 'before' => 'admin'], function() {
        Route::get('index', ['as' => 'backend.userbooking.index', 'uses' => 'Backend\UserBookingController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.userbooking.index.json', 'uses' => 'Backend\UserBookingController@postIndexJson']);
        Route::get('create', ['as' => 'backend.userbooking.create', 'uses' => 'Backend\UserBookingController@getCreate']);
        Route::post('create', ['as' => 'backend.userbooking.store', 'uses' => 'Backend\UserBookingController@postCreate']);
        Route::get('{id}', ['as' => 'backend.userbooking.show', 'uses' => 'Backend\UserBookingController@getView']);
        Route::get('{id}/update', ['as' => 'backend.userbooking.edit', 'uses' => 'Backend\UserBookingController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.userbooking.update', 'uses' => 'Backend\UserBookingController@postUpdate']);
        
        Route::get('exportxls', ['as' => 'backend.userbooking.exportxls', 'uses' => 'Backend\UserBookingController@exportToExcel']);
    });

    // ----- MARKETING -----
    // Newsletter Subscribers
    Route::group(['prefix' => 'newslettersubscribers'], function() {
        Route::get('index', ['as' => 'backend.newslettersubscribers.index', 'uses' => 'Backend\NewsletterSubscriberController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.newslettersubscribers.index.json', 'uses' => 'Backend\NewsletterSubscriberController@postIndexJson']);
        Route::get('{id}/update', ['as' => 'backend.newslettersubscribers.edit', 'uses' => 'Backend\NewsletterSubscriberController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.newslettersubscribers.update', 'uses' => 'Backend\NewsletterSubscriberController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.newslettersubscribers.destroy', 'uses' => 'Backend\NewsletterSubscriberController@postDelete']);
        Route::get('exportxls', ['as' => 'backend.newslettersubscribers.exportxls', 'uses' => 'Backend\NewsletterSubscriberController@exportToExcel']);
    });

    // Newsletter 
    Route::group(['prefix' => 'newsletter'], function() {
        Route::get('index', ['as' => 'backend.newsletter.index', 'uses' => 'Backend\NewsletterController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.newsletter.index.json', 'uses' => 'Backend\NewsletterController@postIndexJson']);
        Route::get('create', ['as' => 'backend.newsletter.create', 'uses' => 'Backend\NewsletterController@getCreate']);
        Route::post('create', ['as' => 'backend.newsletter.store', 'uses' => 'Backend\NewsletterController@postCreate']);
        Route::get('{id}', ['as' => 'backend.newsletter.show', 'uses' => 'Backend\NewsletterController@getView']);
        Route::get('{id}/preview', ['as' => 'backend.newsletter.preview', 'uses' => 'Backend\NewsletterController@getPreview']);
        Route::get('{id}/update', ['as' => 'backend.newsletter.edit', 'uses' => 'Backend\NewsletterController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.newsletter.update', 'uses' => 'Backend\NewsletterController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.newsletter.destroy', 'uses' => 'Backend\NewsletterController@postDelete']);
    });

    // ----- MASTERDATA -----
    // Country Management
    Route::group(['prefix' => 'country', 'before' => 'admin'], function () {
        Route::get('index', ['as' => 'backend.country.index', 'uses' => 'Backend\CountryController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.country.index.json', 'uses' => 'Backend\CountryController@postIndexJson']);
        Route::get('optionsjson', ['as' => 'backend.country.options.json', 'uses' => 'Backend\CountryController@getListJson']);
        Route::get('create', ['as' => 'backend.country.create', 'uses' => 'Backend\CountryController@getCreate']);
        Route::post('create', ['as' => 'backend.country.store', 'uses' => 'Backend\CountryController@postCreate']);
        Route::get('{id}', ['as' => 'backend.country.show', 'uses' => 'Backend\CountryController@getView']);
        Route::get('{id}/update', ['as' => 'backend.country.edit', 'uses' => 'Backend\CountryController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.country.update', 'uses' => 'Backend\CountryController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.country.destroy', 'uses' => 'Backend\CountryController@postDelete']);

        Route::post('{id}/select', ['as' => 'backend.country.select', 'uses' => 'Backend\CountryController@postSelect']);
        Route::post('destroyall', ['as' => 'backend.country.destroyall', 'uses' => 'Backend\CountryController@postDeleteAll']);
    });

    // City Management
    Route::group(['prefix' => 'city', 'before' => 'admin'], function () {
        Route::get('index', ['as' => 'backend.city.index', 'uses' => 'Backend\CityController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.city.index.json', 'uses' => 'Backend\CityController@postIndexJson']);
        Route::get('create', ['as' => 'backend.city.create', 'uses' => 'Backend\CityController@getCreate']);
        Route::post('create', ['as' => 'backend.city.store', 'uses' => 'Backend\CityController@postCreate']);
        Route::get('{id}', ['as' => 'backend.city.show', 'uses' => 'Backend\CityController@getView']);
        Route::get('{id}/update', ['as' => 'backend.city.edit', 'uses' => 'Backend\CityController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.city.update', 'uses' => 'Backend\CityController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.city.destroy', 'uses' => 'Backend\CityController@postDelete']);

        Route::post('{id}/select', ['as' => 'backend.city.select', 'uses' => 'Backend\CityController@postSelect']);
        Route::post('destroyall', ['as' => 'backend.city.destroyall', 'uses' => 'Backend\CityController@postDeleteAll']);
    });

    // Price Inclusion Management
    Route::group(['prefix' => 'priceinclusion', 'before' => 'admin'], function () {
        Route::get('index', ['as' => 'backend.priceinclusion.index', 'uses' => 'Backend\PriceInclusionController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.priceinclusion.index.json', 'uses' => 'Backend\PriceInclusionController@postIndexJson']);
        Route::get('create', ['as' => 'backend.priceinclusion.create', 'uses' => 'Backend\PriceInclusionController@getCreate']);
        Route::post('create', ['as' => 'backend.priceinclusion.store', 'uses' => 'Backend\PriceInclusionController@postCreate']);
        Route::get('{id}', ['as' => 'backend.priceinclusion.show', 'uses' => 'Backend\PriceInclusionController@getView']);
        Route::get('{id}/update', ['as' => 'backend.priceinclusion.edit', 'uses' => 'Backend\PriceInclusionController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.priceinclusion.update', 'uses' => 'Backend\PriceInclusionController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.priceinclusion.destroy', 'uses' => 'Backend\PriceInclusionController@postDelete']);

        Route::post('{id}/select', ['as' => 'backend.priceinclusion.select', 'uses' => 'Backend\PriceInclusionController@postSelect']);
        Route::post('destroyall', ['as' => 'backend.priceinclusion.destroyall', 'uses' => 'Backend\PriceInclusionController@postDeleteAll']);
    });

    // Talent Category Management
    Route::group(['prefix' => 'talentcategory', 'before' => 'admin'], function () {
        Route::get('index', ['as' => 'backend.talentcategory.index', 'uses' => 'Backend\TalentCategoryController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.talentcategory.index.json', 'uses' => 'Backend\TalentCategoryController@postIndexJson']);
        Route::get('optionsjson', ['as' => 'backend.talentcategory.options.json', 'uses' => 'Backend\TalentCategoryController@getListJson']);
        Route::get('create', ['as' => 'backend.talentcategory.create', 'uses' => 'Backend\TalentCategoryController@getCreate']);
        Route::post('create', ['as' => 'backend.talentcategory.store', 'uses' => 'Backend\TalentCategoryController@postCreate']);
        Route::get('{id}', ['as' => 'backend.talentcategory.show', 'uses' => 'Backend\TalentCategoryController@getView']);
        Route::get('{id}/update', ['as' => 'backend.talentcategory.edit', 'uses' => 'Backend\TalentCategoryController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.talentcategory.update', 'uses' => 'Backend\TalentCategoryController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.talentcategory.destroy', 'uses' => 'Backend\TalentCategoryController@postDelete']);

        Route::post('{id}/select', ['as' => 'backend.talentcategory.select', 'uses' => 'Backend\TalentCategoryController@postSelect']);
        Route::post('destroyall', ['as' => 'backend.talentcategory.destroyall', 'uses' => 'Backend\TalentCategoryController@postDeleteAll']);
    });

    // Talent Expertise Management
    Route::group(['prefix' => 'talentexpertise', 'before' => 'admin'], function () {
        Route::get('index', ['as' => 'backend.talentexpertise.index', 'uses' => 'Backend\TalentExpertiseController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.talentexpertise.index.json', 'uses' => 'Backend\TalentExpertiseController@postIndexJson']);
        Route::get('optionsjson', ['as' => 'backend.talentexpertise.options.json', 'uses' => 'Backend\TalentExpertiseController@getListJson']);
        Route::get('create', ['as' => 'backend.talentexpertise.create', 'uses' => 'Backend\TalentExpertiseController@getCreate']);
        Route::post('create', ['as' => 'backend.talentexpertise.store', 'uses' => 'Backend\TalentExpertiseController@postCreate']);
        Route::get('{id}', ['as' => 'backend.talentexpertise.show', 'uses' => 'Backend\TalentExpertiseController@getView']);
        Route::get('{id}/update', ['as' => 'backend.talentexpertise.edit', 'uses' => 'Backend\TalentExpertiseController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.talentexpertise.update', 'uses' => 'Backend\TalentExpertiseController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.talentexpertise.destroy', 'uses' => 'Backend\TalentExpertiseController@postDelete']);

        Route::post('{id}/select', ['as' => 'backend.talentexpertise.select', 'uses' => 'Backend\TalentExpertiseController@postSelect']);
        Route::post('destroyall', ['as' => 'backend.talentexpertise.destroyall', 'uses' => 'Backend\TalentExpertiseController@postDeleteAll']);
    });

    // Currency Management
    Route::group(['prefix' => 'currency', 'before' => 'admin'], function () {
        Route::get('index', ['as' => 'backend.currency.index', 'uses' => 'Backend\CurrencyController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.currency.index.json', 'uses' => 'Backend\CurrencyController@postIndexJson']);
        Route::get('create', ['as' => 'backend.currency.create', 'uses' => 'Backend\CurrencyController@getCreate']);
        Route::post('create', ['as' => 'backend.currency.store', 'uses' => 'Backend\CurrencyController@postCreate']);
        Route::get('{id}', ['as' => 'backend.currency.show', 'uses' => 'Backend\CurrencyController@getView']);
        Route::get('{id}/update', ['as' => 'backend.currency.edit', 'uses' => 'Backend\CurrencyController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.currency.update', 'uses' => 'Backend\CurrencyController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.currency.destroy', 'uses' => 'Backend\CurrencyController@postDelete']);

        Route::post('{id}/select', ['as' => 'backend.currency.select', 'uses' => 'Backend\CurrencyController@postSelect']);
        Route::post('destroyall', ['as' => 'backend.currency.destroyall', 'uses' => 'Backend\CurrencyController@postDeleteAll']);
    });

    // ----- WEB CONTENT -----
    // Faq Category Management
    Route::group(['prefix' => 'faqcategory', 'before' => 'admin'], function() {
        Route::get('index', ['as' => 'backend.faqcategory.index', 'uses' => 'Backend\FaqCategoryController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.faqcategory.index.json', 'uses' => 'Backend\FaqCategoryController@postIndexJson']);
        Route::get('create', ['as' => 'backend.faqcategory.create', 'uses' => 'Backend\FaqCategoryController@getCreate']);
        Route::post('create', ['as' => 'backend.faqcategory.store', 'uses' => 'Backend\FaqCategoryController@postCreate']);
        Route::get('{id}/update', ['as' => 'backend.faqcategory.edit', 'uses' => 'Backend\FaqCategoryController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.faqcategory.update', 'uses' => 'Backend\FaqCategoryController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.faqcategory.destroy', 'uses' => 'Backend\FaqCategoryController@postDelete']);

        Route::post('{id}/select', ['as' => 'backend.faqcategory.select', 'uses' => 'Backend\FaqCategoryController@postSelect']);
        Route::post('destroyall', ['as' => 'backend.faqcategory.destroyall', 'uses' => 'Backend\FaqCategoryController@postDeleteAll']);
    });

    // Faq Management
    Route::group(['prefix' => 'faq', 'before' => 'admin'], function() {
        Route::get('index', ['as' => 'backend.faq.index', 'uses' => 'Backend\FaqController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.faq.index.json', 'uses' => 'Backend\FaqController@postIndexJson']);
        Route::get('create', ['as' => 'backend.faq.create', 'uses' => 'Backend\FaqController@getCreate']);
        Route::post('create', ['as' => 'backend.faq.store', 'uses' => 'Backend\FaqController@postCreate']);
        Route::get('{id}', ['as' => 'backend.faq.show', 'uses' => 'Backend\FaqController@getView']);
        Route::get('{id}/update', ['as' => 'backend.faq.edit', 'uses' => 'Backend\FaqController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.faq.update', 'uses' => 'Backend\FaqController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.faq.destroy', 'uses' => 'Backend\FaqController@postDelete']);

        Route::post('{id}/select', ['as' => 'backend.faq.select', 'uses' => 'Backend\FaqController@postSelect']);
        Route::post('destroyall', ['as' => 'backend.faq.destroyall', 'uses' => 'Backend\FaqController@postDeleteAll']);
    });

    // Contact Message Management
    Route::group(['prefix' => 'contactmessage', 'before' => 'admin'], function () {
        Route::get('/', ['as' => 'backend.contactmessage', function() {
            return Redirect::route('backend.contactmessage.index');
        }]);
        Route::get('index', ['as' => 'backend.contactmessage.index', 'uses' => 'Backend\ContactMessageController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.contactmessage.index.json', 'uses' => 'Backend\ContactMessageController@postIndexJson']);
        Route::get('{id}', ['as' => 'backend.contactmessage.show', 'uses' => 'Backend\ContactMessageController@getView']);
        Route::post('{id}/destroy', ['as' => 'backend.contactmessage.destroy', 'uses' => 'Backend\ContactMessageController@postDelete']);
        // Extended from standard action
        Route::post('{id}', ['as' => 'backend.contactmessage.show.reply', 'uses' => 'Backend\ContactMessageController@postReply']);
    });

});

// Route Frontend
include ("routes_frontend.php");
