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
    case 'testing':
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
Route::group(['prefix' => 'admin', 'middleware' => ['web', 'admin']], function() {
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
    Route::get('/exportscenario', ['as' => 'backend.home.exportscenario', 'uses' => 'Backend\HomeController@exportToExcel']);
    Route::get('pattern', ['as' => 'backend.home.pattern', 'uses' => 'Backend\HomeController@pattern']);
    Route::get('companyfetcher/', array(
        'as' => 'backend.company.fetcher',
        function (){
            $countries = App\Models\Company::get(['id', 'name']);
            if ($countries) {
                return Response::json($countries);
            }
        }
    ));

    // ----- BACKEND -----
    // Page Management
    Route::group(['prefix' => 'pages', 'before' => 'admin'], function() {
        Route::get('index', ['as' => 'backend.pages.index', 'uses' => 'Backend\PageController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.pages.index.json', 'uses' => 'Backend\PageController@postIndexJson']);
        Route::get('create', ['as' => 'backend.pages.create', 'uses' => 'Backend\PageController@getCreate']);
        Route::post('create', ['as' => 'backend.pages.store', 'uses' => 'Backend\PageController@postCreate']);
        Route::get('{id}', ['as' => 'backend.pages.show', 'uses' => 'Backend\PageController@getView']);
        Route::get('{id}/update', ['as' => 'backend.pages.edit', 'uses' => 'Backend\PageController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.pages.update', 'uses' => 'Backend\PageController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.pages.destroy', 'uses' => 'Backend\PageController@postDelete']);
    });
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

    // Tips Management
    Route::group(['prefix' => 'tips', 'before' => 'admin'], function() {
        Route::get('index', ['as' => 'backend.tips.index', 'uses' => 'Backend\TipsController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.tips.index.json', 'uses' => 'Backend\TipsController@postIndexJson']);
        Route::get('create', ['as' => 'backend.tips.create', 'uses' => 'Backend\TipsController@getCreate']);
        Route::post('create', ['as' => 'backend.tips.store', 'uses' => 'Backend\TipsController@postCreate']);
        Route::get('{id}', ['as' => 'backend.tips.show', 'uses' => 'Backend\TipsController@getView']);
        Route::get('{id}/update', ['as' => 'backend.tips.edit', 'uses' => 'Backend\TipsController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.tips.update', 'uses' => 'Backend\TipsController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.tips.destroy', 'uses' => 'Backend\TipsController@postDelete']);
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

    // ----- BUSSINESS -----
    // Company Management
    Route::group(['prefix' => 'company', 'before' => 'admin'], function () {
        Route::get('index', ['as' => 'backend.company.index', 'uses' => 'Backend\CompanyController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.company.index.json', 'uses' => 'Backend\CompanyController@postIndexJson']);
        Route::get('create', ['as' => 'backend.company.create', 'uses' => 'Backend\CompanyController@getCreate']);
        Route::post('create', ['as' => 'backend.company.store', 'uses' => 'Backend\CompanyController@postCreate']);
        Route::get('{id}', ['as' => 'backend.company.show', 'uses' => 'Backend\CompanyController@getView']);
        Route::get('{id}/update', ['as' => 'backend.company.edit', 'uses' => 'Backend\CompanyController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.company.update', 'uses' => 'Backend\CompanyController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.company.destroy', 'uses' => 'Backend\CompanyController@postDelete']);
        Route::get('{id}/profile', ['as' => 'backend.company.profile', 'uses' => 'Backend\CompanyController@getViewProfile']);
    });
    // Company User Management
    Route::group(['prefix' => 'companyuser', 'before' => 'admin'], function () {
        Route::get('index', ['as' => 'backend.companyuser.index', 'uses' => 'Backend\CompanyUserController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.companyuser.index.json', 'uses' => 'Backend\CompanyUserController@postIndexJson']);
        Route::get('create', ['as' => 'backend.companyuser.create', 'uses' => 'Backend\CompanyUserController@getCreate']);
        Route::post('create', ['as' => 'backend.companyuser.store', 'uses' => 'Backend\CompanyUserController@postCreate']);
        Route::get('{id}', ['as' => 'backend.companyuser.show', 'uses' => 'Backend\CompanyUserController@getView']);
        Route::get('{id}/update', ['as' => 'backend.companyuser.edit', 'uses' => 'Backend\CompanyUserController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.companyuser.update', 'uses' => 'Backend\CompanyUserController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.companyuser.destroy', 'uses' => 'Backend\CompanyUserController@postDelete']);
    });
    // Company Industry Management
    Route::group(['prefix' => 'companyindustry', 'before' => 'admin'], function () {
        Route::get('index', ['as' => 'backend.companyindustry.index', 'uses' => 'Backend\CompanyIndustryController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.companyindustry.index.json', 'uses' => 'Backend\CompanyIndustryController@postIndexJson']);
        Route::get('create', ['as' => 'backend.companyindustry.create', 'uses' => 'Backend\CompanyIndustryController@getCreate']);
        Route::post('create', ['as' => 'backend.companyindustry.store', 'uses' => 'Backend\CompanyIndustryController@postCreate']);
        Route::get('{id}', ['as' => 'backend.companyindustry.show', 'uses' => 'Backend\CompanyIndustryController@getView']);
        Route::get('{id}/update', ['as' => 'backend.companyindustry.edit', 'uses' => 'Backend\CompanyIndustryController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.companyindustry.update', 'uses' => 'Backend\CompanyIndustryController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.companyindustry.destroy', 'uses' => 'Backend\CompanyIndustryController@postDelete']);
    });
    // Company Gallery Management
    Route::group(['prefix' => 'companygallery', 'before' => 'admin'], function () {
        Route::get('index', ['as' => 'backend.companygallery.index', 'uses' => 'Backend\CompanyGalleryController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.companygallery.index.json', 'uses' => 'Backend\CompanyGalleryController@postIndexJson']);
        Route::get('create', ['as' => 'backend.companygallery.create', 'uses' => 'Backend\CompanyGalleryController@getCreate']);
        Route::post('create', ['as' => 'backend.companygallery.store', 'uses' => 'Backend\CompanyGalleryController@postCreate']);
        Route::get('{id}', ['as' => 'backend.companygallery.show', 'uses' => 'Backend\CompanyGalleryController@getView']);
        Route::get('{id}/update', ['as' => 'backend.companygallery.edit', 'uses' => 'Backend\CompanyGalleryController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.companygallery.update', 'uses' => 'Backend\CompanyGalleryController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.companygallery.destroy', 'uses' => 'Backend\CompanyGalleryController@postDelete']);
    });
    // Vacancy Management
    Route::group(['prefix' => 'vacancy', 'before' => 'admin'], function () {
        Route::get('index', ['as' => 'backend.vacancy.index', 'uses' => 'Backend\VacancyController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.vacancy.index.json', 'uses' => 'Backend\VacancyController@postIndexJson']);
        Route::get('create', ['as' => 'backend.vacancy.create', 'uses' => 'Backend\VacancyController@getCreate']);
        Route::post('create', ['as' => 'backend.vacancy.store', 'uses' => 'Backend\VacancyController@postCreate']);
        Route::get('{id}', ['as' => 'backend.vacancy.show', 'uses' => 'Backend\VacancyController@getView']);
        Route::get('{id}/update', ['as' => 'backend.vacancy.edit', 'uses' => 'Backend\VacancyController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.vacancy.update', 'uses' => 'Backend\VacancyController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.vacancy.destroy', 'uses' => 'Backend\VacancyController@postDelete']);
        Route::get('{id}/specify', ['as' => 'backend.vacancy.specify', 'uses' => 'Backend\VacancyController@getViewSpecify']);
        Route::get('{id}/majors/tags/json', ['as' => 'backend.vacancy.majors.tags.json', 'uses' => 'Backend\VacancyController@getTagsMajors']);
        Route::get('{id}/candidate/locations/tags/json', ['as' => 'backend.vacancy.candidate.locations.tags.json', 'uses' => 'Backend\VacancyController@getTagsCandidateLocations']);

        Route::get('degree', array(
            'as' => 'backend.vacancy.degree.fetcher',
            function () {
                $id     = request()->get('id');
                $degree = App\Models\Degree::find($id);
                if ($degree) {
                    $majors = $degree->majors();
                    if ($majors) {
                        return Response::json($majors->get(['id', 'name']));
                    }
                }
                return Response::json([]);
            }
        ));

        Route::get('industry', array('as' => 'backend.vacancy.fetcher.industry', function () {
            $id         = request()->get('id');
            $industries = App\Models\Industry::find($id);
            $data       = [];
            if ($industries) {
                $i = 0;
                foreach ($industries as $industry) {
                    $data[$i]['id']          = $industry->id;
                    $data[$i]['name']        = $industry->name;
                    $data[$i]['industries']  = $industry->industries()->get(['id', 'name']);
                    $i++;
                }

                return Response::json($data);
            }

            return Response::json([]);
        }));

        Route::get('environment', array('as' => 'backend.vacancy.fetcher.environment', function () {
            $id         = request()->get('id');
            $industries = App\Models\Environment::find($id);
            $data       = [];
            if ($industries) {
                $i = 0;
                foreach ($industries as $environment) {
                    $data[$i]['id']          = $environment->id;
                    $data[$i]['name']        = $environment->name;
                    $data[$i]['professions'] = $environment->professions()->get(['id', 'name']);
                    $i++;
                }

                return Response::json($data);
            }

            return Response::json([]);
        }));
    });
    // Vacancy Language Management
//    Route::group(['prefix' => 'vacancylanguage', 'before' => 'admin'], function () {
//        Route::get('index', ['as' => 'backend.vacancylanguage.index', 'uses' => 'Backend\VacancyLanguageController@getIndex']);
//        Route::post('indexjson', ['as' => 'backend.vacancylanguage.index.json', 'uses' => 'Backend\VacancyLanguageController@postIndexJson']);
//        Route::get('create', ['as' => 'backend.vacancylanguage.create', 'uses' => 'Backend\VacancyLanguageController@getCreate']);
//        Route::post('create', ['as' => 'backend.vacancylanguage.store', 'uses' => 'Backend\VacancyLanguageController@postCreate']);
//        Route::get('{id}', ['as' => 'backend.vacancylanguage.show', 'uses' => 'Backend\VacancyLanguageController@getView']);
//        Route::get('{id}/update', ['as' => 'backend.vacancylanguage.edit', 'uses' => 'Backend\VacancyLanguageController@getUpdate']);
//        Route::post('{id}/update', ['as' => 'backend.vacancylanguage.update', 'uses' => 'Backend\VacancyLanguageController@postUpdate']);
//        Route::post('{id}/destroy', ['as' => 'backend.vacancylanguage.destroy', 'uses' => 'Backend\VacancyLanguageController@postDelete']);
//    });
    // Vacancy Degree Management
//    Route::group(['prefix' => 'vacancydegree', 'before' => 'admin'], function () {
//        Route::get('index', ['as' => 'backend.vacancydegree.index', 'uses' => 'Backend\VacancyDegreeController@getIndex']);
//        Route::post('indexjson', ['as' => 'backend.vacancydegree.index.json', 'uses' => 'Backend\VacancyDegreeController@postIndexJson']);
//        Route::get('create', ['as' => 'backend.vacancydegree.create', 'uses' => 'Backend\VacancyDegreeController@getCreate']);
//        Route::post('create', ['as' => 'backend.vacancydegree.store', 'uses' => 'Backend\VacancyDegreeController@postCreate']);
//        Route::get('{id}', ['as' => 'backend.vacancydegree.show', 'uses' => 'Backend\VacancyDegreeController@getView']);
//        Route::get('{id}/update', ['as' => 'backend.vacancydegree.edit', 'uses' => 'Backend\VacancyDegreeController@getUpdate']);
//        Route::post('{id}/update', ['as' => 'backend.vacancydegree.update', 'uses' => 'Backend\VacancyDegreeController@postUpdate']);
//        Route::post('{id}/destroy', ['as' => 'backend.vacancydegree.destroy', 'uses' => 'Backend\VacancyDegreeController@postDelete']);
//    });
    // Vacancy Candidate Management
//    Route::group(['prefix' => 'vacancycandidate', 'before' => 'admin'], function () {
//        Route::get('index', ['as' => 'backend.vacancycandidate.index', 'uses' => 'Backend\VacancyCandidateController@getIndex']);
//        Route::post('indexjson', ['as' => 'backend.vacancycandidate.index.json', 'uses' => 'Backend\VacancyCandidateController@postIndexJson']);
//        Route::get('create', ['as' => 'backend.vacancycandidate.create', 'uses' => 'Backend\VacancyCandidateController@getCreate']);
//        Route::post('create', ['as' => 'backend.vacancycandidate.store', 'uses' => 'Backend\VacancyCandidateController@postCreate']);
//        Route::get('{id}', ['as' => 'backend.vacancycandidate.show', 'uses' => 'Backend\VacancyCandidateController@getView']);
//        Route::get('{id}/update', ['as' => 'backend.vacancycandidate.edit', 'uses' => 'Backend\VacancyCandidateController@getUpdate']);
//        Route::post('{id}/update', ['as' => 'backend.vacancycandidate.update', 'uses' => 'Backend\VacancyCandidateController@postUpdate']);
//        Route::post('{id}/destroy', ['as' => 'backend.vacancycandidate.destroy', 'uses' => 'Backend\VacancyCandidateController@postDelete']);
//    });
    // Vacancy Location Management
//    Route::group(['prefix' => 'vacancylocation', 'before' => 'admin'], function () {
//        Route::get('index', ['as' => 'backend.vacancylocation.index', 'uses' => 'Backend\VacancyLocationController@getIndex']);
//        Route::post('indexjson', ['as' => 'backend.vacancylocation.index.json', 'uses' => 'Backend\VacancyLocationController@postIndexJson']);
//        Route::get('create', ['as' => 'backend.vacancylocation.create', 'uses' => 'Backend\VacancyLocationController@getCreate']);
//        Route::post('create', ['as' => 'backend.vacancylocation.store', 'uses' => 'Backend\VacancyLocationController@postCreate']);
//        Route::get('{id}', ['as' => 'backend.vacancylocation.show', 'uses' => 'Backend\VacancyLocationController@getView']);
//        Route::get('{id}/update', ['as' => 'backend.vacancylocation.edit', 'uses' => 'Backend\VacancyLocationController@getUpdate']);
//        Route::post('{id}/update', ['as' => 'backend.vacancylocation.update', 'uses' => 'Backend\VacancyLocationController@postUpdate']);
//        Route::post('{id}/destroy', ['as' => 'backend.vacancylocation.destroy', 'uses' => 'Backend\VacancyLocationController@postDelete']);
//    });

    // ----- MASTERDATA -----
    // Province Management
    Route::group(['prefix' => 'region', 'before' => 'admin'], function () {
        Route::get('index', ['as' => 'backend.region.index', 'uses' => 'Backend\RegionController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.region.index.json', 'uses' => 'Backend\RegionController@postIndexJson']);
        Route::get('create', ['as' => 'backend.region.create', 'uses' => 'Backend\RegionController@getCreate']);
        Route::post('create', ['as' => 'backend.region.store', 'uses' => 'Backend\RegionController@postCreate']);
        Route::get('{id}', ['as' => 'backend.region.show', 'uses' => 'Backend\RegionController@getView']);
        Route::get('{id}/update', ['as' => 'backend.region.edit', 'uses' => 'Backend\RegionController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.region.update', 'uses' => 'Backend\RegionController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.region.destroy', 'uses' => 'Backend\RegionController@postDelete']);
    });

    // Kabkota Management
    Route::group(['prefix' => 'city', 'before' => 'admin'], function () {
        Route::get('index', ['as' => 'backend.city.index', 'uses' => 'Backend\CityController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.city.index.json', 'uses' => 'Backend\CityController@postIndexJson']);
        Route::get('create', ['as' => 'backend.city.create', 'uses' => 'Backend\CityController@getCreate']);
        Route::post('create', ['as' => 'backend.city.store', 'uses' => 'Backend\CityController@postCreate']);
        Route::get('{id}', ['as' => 'backend.city.show', 'uses' => 'Backend\CityController@getView']);
        Route::get('{id}/update', ['as' => 'backend.city.edit', 'uses' => 'Backend\CityController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.city.update', 'uses' => 'Backend\CityController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.city.destroy', 'uses' => 'Backend\CityController@postDelete']);
    });

    // Kecamatan Management
    Route::group(['prefix' => 'area', 'before' => 'admin'], function () {
        Route::get('index', ['as' => 'backend.area.index', 'uses' => 'Backend\AreaController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.area.index.json', 'uses' => 'Backend\AreaController@postIndexJson']);
        Route::get('create', ['as' => 'backend.area.create', 'uses' => 'Backend\AreaController@getCreate']);
        Route::post('create', ['as' => 'backend.area.store', 'uses' => 'Backend\AreaController@postCreate']);
        Route::get('{id}', ['as' => 'backend.area.show', 'uses' => 'Backend\AreaController@getView']);
        Route::get('{id}/update', ['as' => 'backend.area.edit', 'uses' => 'Backend\AreaController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.area.update', 'uses' => 'Backend\AreaController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.area.destroy', 'uses' => 'Backend\AreaController@postDelete']);
    });

    // Country Management
    Route::group(['prefix' => 'country', 'before' => 'admin'], function () {
        Route::get('index', ['as' => 'backend.country.index', 'uses' => 'Backend\CountryController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.country.index.json', 'uses' => 'Backend\CountryController@postIndexJson']);
        Route::get('create', ['as' => 'backend.country.create', 'uses' => 'Backend\CountryController@getCreate']);
        Route::post('create', ['as' => 'backend.country.store', 'uses' => 'Backend\CountryController@postCreate']);
        Route::get('{id}', ['as' => 'backend.country.show', 'uses' => 'Backend\CountryController@getView']);
        Route::get('{id}/update', ['as' => 'backend.country.edit', 'uses' => 'Backend\CountryController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.country.update', 'uses' => 'Backend\CountryController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.country.destroy', 'uses' => 'Backend\CountryController@postDelete']);
    });

    // Environment Management
    Route::group(['prefix' => 'environment', 'before' => 'admin'], function () {
        Route::get('index', ['as' => 'backend.environment.index', 'uses' => 'Backend\EnvironmentController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.environment.index.json', 'uses' => 'Backend\EnvironmentController@postIndexJson']);
        Route::get('create', ['as' => 'backend.environment.create', 'uses' => 'Backend\EnvironmentController@getCreate']);
        Route::post('create', ['as' => 'backend.environment.store', 'uses' => 'Backend\EnvironmentController@postCreate']);
        Route::get('{id}', ['as' => 'backend.environment.show', 'uses' => 'Backend\EnvironmentController@getView']);
        Route::get('{id}/update', ['as' => 'backend.environment.edit', 'uses' => 'Backend\EnvironmentController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.environment.update', 'uses' => 'Backend\EnvironmentController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.environment.destroy', 'uses' => 'Backend\EnvironmentController@postDelete']);
    });

    // Industry Management
    Route::group(['prefix' => 'industry', 'before' => 'admin'], function () {
        Route::get('index', ['as' => 'backend.industry.index', 'uses' => 'Backend\IndustryController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.industry.index.json', 'uses' => 'Backend\IndustryController@postIndexJson']);
        Route::get('create', ['as' => 'backend.industry.create', 'uses' => 'Backend\IndustryController@getCreate']);
        Route::post('create', ['as' => 'backend.industry.store', 'uses' => 'Backend\IndustryController@postCreate']);
        Route::get('{id}', ['as' => 'backend.industry.show', 'uses' => 'Backend\IndustryController@getView']);
        Route::get('{id}/update', ['as' => 'backend.industry.edit', 'uses' => 'Backend\IndustryController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.industry.update', 'uses' => 'Backend\IndustryController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.industry.destroy', 'uses' => 'Backend\IndustryController@postDelete']);
    });

    // Profession Management
    Route::group(['prefix' => 'profession', 'before' => 'admin'], function () {
        Route::get('index', ['as' => 'backend.profession.index', 'uses' => 'Backend\ProfessionController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.profession.index.json', 'uses' => 'Backend\ProfessionController@postIndexJson']);
        Route::get('create', ['as' => 'backend.profession.create', 'uses' => 'Backend\ProfessionController@getCreate']);
        Route::post('create', ['as' => 'backend.profession.store', 'uses' => 'Backend\ProfessionController@postCreate']);
        Route::get('{id}', ['as' => 'backend.profession.show', 'uses' => 'Backend\ProfessionController@getView']);
        Route::get('{id}/update', ['as' => 'backend.profession.edit', 'uses' => 'Backend\ProfessionController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.profession.update', 'uses' => 'Backend\ProfessionController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.profession.destroy', 'uses' => 'Backend\ProfessionController@postDelete']);
    });

    // Job Level Management
    Route::group(['prefix' => 'joblevel', 'before' => 'admin'], function () {
        Route::get('index', ['as' => 'backend.joblevel.index', 'uses' => 'Backend\JobLevelController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.joblevel.index.json', 'uses' => 'Backend\JobLevelController@postIndexJson']);
        Route::get('create', ['as' => 'backend.joblevel.create', 'uses' => 'Backend\JobLevelController@getCreate']);
        Route::post('create', ['as' => 'backend.joblevel.store', 'uses' => 'Backend\JobLevelController@postCreate']);
        Route::get('{id}', ['as' => 'backend.joblevel.show', 'uses' => 'Backend\JobLevelController@getView']);
        Route::get('{id}/update', ['as' => 'backend.joblevel.edit', 'uses' => 'Backend\JobLevelController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.joblevel.update', 'uses' => 'Backend\JobLevelController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.joblevel.destroy', 'uses' => 'Backend\JobLevelController@postDelete']);
    });

    // Skill Management
    Route::group(['prefix' => 'university', 'before' => 'admin'], function () {
        Route::get('index', ['as' => 'backend.university.index', 'uses' => 'Backend\UniversityController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.university.index.json', 'uses' => 'Backend\UniversityController@postIndexJson']);
        Route::get('create', ['as' => 'backend.university.create', 'uses' => 'Backend\UniversityController@getCreate']);
        Route::post('create', ['as' => 'backend.university.store', 'uses' => 'Backend\UniversityController@postCreate']);
        Route::get('{id}', ['as' => 'backend.university.show', 'uses' => 'Backend\UniversityController@getView']);
        Route::get('{id}/update', ['as' => 'backend.university.edit', 'uses' => 'Backend\UniversityController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.university.update', 'uses' => 'Backend\UniversityController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.university.destroy', 'uses' => 'Backend\UniversityController@postDelete']);
    });

    // Skill Management
    Route::group(['prefix' => 'skill', 'before' => 'admin'], function () {
        Route::get('index', ['as' => 'backend.skill.index', 'uses' => 'Backend\SkillController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.skill.index.json', 'uses' => 'Backend\SkillController@postIndexJson']);
        Route::get('create', ['as' => 'backend.skill.create', 'uses' => 'Backend\SkillController@getCreate']);
        Route::post('create', ['as' => 'backend.skill.store', 'uses' => 'Backend\SkillController@postCreate']);
        Route::get('{id}', ['as' => 'backend.skill.show', 'uses' => 'Backend\SkillController@getView']);
        Route::get('{id}/update', ['as' => 'backend.skill.edit', 'uses' => 'Backend\SkillController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.skill.update', 'uses' => 'Backend\SkillController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.skill.destroy', 'uses' => 'Backend\SkillController@postDelete']);
    });

    // Degree Management
    Route::group(['prefix' => 'degree', 'before' => 'admin'], function () {
        Route::get('index', ['as' => 'backend.degree.index', 'uses' => 'Backend\DegreeController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.degree.index.json', 'uses' => 'Backend\DegreeController@postIndexJson']);
        Route::get('create', ['as' => 'backend.degree.create', 'uses' => 'Backend\DegreeController@getCreate']);
        Route::post('create', ['as' => 'backend.degree.store', 'uses' => 'Backend\DegreeController@postCreate']);
        Route::get('{id}', ['as' => 'backend.degree.show', 'uses' => 'Backend\DegreeController@getView']);
        Route::get('{id}/update', ['as' => 'backend.degree.edit', 'uses' => 'Backend\DegreeController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.degree.update', 'uses' => 'Backend\DegreeController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.degree.destroy', 'uses' => 'Backend\DegreeController@postDelete']);
    });

    // Major Management
    Route::group(['prefix' => 'major', 'before' => 'admin'], function () {
        Route::get('index', ['as' => 'backend.major.index', 'uses' => 'Backend\MajorController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.major.index.json', 'uses' => 'Backend\MajorController@postIndexJson']);
        Route::get('create', ['as' => 'backend.major.create', 'uses' => 'Backend\MajorController@getCreate']);
        Route::post('create', ['as' => 'backend.major.store', 'uses' => 'Backend\MajorController@postCreate']);
        Route::get('{id}', ['as' => 'backend.major.show', 'uses' => 'Backend\MajorController@getView']);
        Route::get('{id}/update', ['as' => 'backend.major.edit', 'uses' => 'Backend\MajorController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.major.update', 'uses' => 'Backend\MajorController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.major.destroy', 'uses' => 'Backend\MajorController@postDelete']);
    });

    // Language Management
    Route::group(['prefix' => 'language', 'before' => 'admin'], function () {
        Route::get('index', ['as' => 'backend.language.index', 'uses' => 'Backend\LanguageController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.language.index.json', 'uses' => 'Backend\LanguageController@postIndexJson']);
        Route::get('create', ['as' => 'backend.language.create', 'uses' => 'Backend\LanguageController@getCreate']);
        Route::post('create', ['as' => 'backend.language.store', 'uses' => 'Backend\LanguageController@postCreate']);
        Route::get('{id}', ['as' => 'backend.language.show', 'uses' => 'Backend\LanguageController@getView']);
        Route::get('{id}/update', ['as' => 'backend.language.edit', 'uses' => 'Backend\LanguageController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.language.update', 'uses' => 'Backend\LanguageController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.language.destroy', 'uses' => 'Backend\LanguageController@postDelete']);
    });

    // Faq Category Management
    Route::group(['prefix' => 'faqcategory', 'before' => 'admin'], function() {
        Route::get('index', ['as' => 'backend.faqcategory.index', 'uses' => 'Backend\FaqCategoryController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.faqcategory.index.json', 'uses' => 'Backend\FaqCategoryController@postIndexJson']);
        Route::get('create', ['as' => 'backend.faqcategory.create', 'uses' => 'Backend\FaqCategoryController@getCreate']);
        Route::post('create', ['as' => 'backend.faqcategory.store', 'uses' => 'Backend\FaqCategoryController@postCreate']);
        Route::get('{id}/update', ['as' => 'backend.faqcategory.edit', 'uses' => 'Backend\FaqCategoryController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.faqcategory.update', 'uses' => 'Backend\FaqCategoryController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.faqcategory.destroy', 'uses' => 'Backend\FaqCategoryController@postDelete']);
    });

    // Banner Management
    Route::group(['prefix' => 'banner', 'before' => 'admin'], function() {
        Route::get('index', ['as' => 'backend.banner.index', 'uses' => 'Backend\BannerController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.banner.index.json', 'uses' => 'Backend\BannerController@postIndexJson']);
        Route::get('create', ['as' => 'backend.banner.create', 'uses' => 'Backend\BannerController@getCreate']);
        Route::post('create', ['as' => 'backend.banner.store', 'uses' => 'Backend\BannerController@postCreate']);
        Route::get('{id}/update', ['as' => 'backend.banner.edit', 'uses' => 'Backend\BannerController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.banner.update', 'uses' => 'Backend\BannerController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.banner.destroy', 'uses' => 'Backend\BannerController@postDelete']);
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
    });

    // Contact Message Management
//    Route::group(['prefix' => 'contactmessage', 'before' => 'admin'], function () {
//        Route::get('/', ['as' => 'backend.contactmessage', function() {
//            return Redirect::route('backend.contactmessage.index');
//        }]);
//        Route::get('index', ['as' => 'backend.contactmessage.index', 'uses' => 'Backend\ContactMessageController@getIndex']);
//        Route::post('json/datatables', ['as' => 'backend.contactmessage.index.json', 'uses' => 'Backend\ContactMessageController@postJsonDatatables']);
//        Route::get('{id}', ['as' => 'backend.contactmessage.show', 'uses' => 'Backend\ContactMessageController@getShow']);
//        Route::post('{id}/destroy', ['as' => 'backend.contactmessage.destroy', 'uses' => 'Backend\ContactMessageController@postDelete']);
//        // Extended from standard action
//        Route::post('{id}', ['as' => 'backend.contactmessage.show.reply', 'uses' => 'Backend\ContactMessageController@postReply']);
//    });

    // ----- PAYMENT -----
    // Payment Method Management
    Route::group(['prefix' => 'paymentmethod', 'before' => 'admin'], function() {
        Route::get('index', ['as' => 'backend.paymentmethod.index', 'uses' => 'Backend\PaymentMethodController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.paymentmethod.index.json', 'uses' => 'Backend\PaymentMethodController@postIndexJson']);
        Route::get('create', ['as' => 'backend.paymentmethod.create', 'uses' => 'Backend\PaymentMethodController@getCreate']);
        Route::post('create', ['as' => 'backend.paymentmethod.store', 'uses' => 'Backend\PaymentMethodController@postCreate']);
        Route::get('{id}/update', ['as' => 'backend.paymentmethod.edit', 'uses' => 'Backend\PaymentMethodController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.paymentmethod.update', 'uses' => 'Backend\PaymentMethodController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.paymentmethod.destroy', 'uses' => 'Backend\PaymentMethodController@postDelete']);
    });

    // Third Party Payment Management.
    Route::group(['prefix' => 'thirdpartymodule', 'before' => 'admin'], function() {
        Route::get('index', ['as' => 'backend.thirdpartymodule.index', 'uses' => 'Backend\ThirdPartyModuleController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.thirdpartymodule.index.json', 'uses' => 'Backend\ThirdPartyModuleController@postIndexJson']);
        Route::get('create', ['as' => 'backend.thirdpartymodule.create', 'uses' => 'Backend\ThirdPartyModuleController@getCreate']);
        Route::post('create', ['as' => 'backend.thirdpartymodule.store', 'uses' => 'Backend\ThirdPartyModuleController@postCreate']);
        Route::get('{id}/update', ['as' => 'backend.thirdpartymodule.edit', 'uses' => 'Backend\ThirdPartyModuleController@getUpdate']);
        Route::post('{id}/update', ['as' => 'backend.thirdpartymodule.update', 'uses' => 'Backend\ThirdPartyModuleController@postUpdate']);
        Route::post('{id}/destroy', ['as' => 'backend.thirdpartymodule.destroy', 'uses' => 'Backend\ThirdPartyModuleController@postDelete']);
    });

    // Third Party Payment Process
    Route::group(['prefix' => 'thirdpartypaymentprocess', 'before' => 'admin'], function() {
        Route::get('index', ['as' => 'backend.thirdpartypaymentprocess.index', 'uses' => 'Backend\ThirdpartyPaymentProcessController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.thirdpartypaymentprocess.index.json', 'uses' => 'Backend\ThirdpartyPaymentProcessController@postIndexJson']);
        Route::get('{id}', ['as' => 'backend.thirdpartypaymentprocess.show', 'uses' => 'Backend\ThirdpartyPaymentProcessController@getView']);
        Route::get('exportxls', ['as' => 'backend.thirdpartypaymentprocess.exportxls', 'uses' => 'Backend\ThirdpartyPaymentProcessController@exportToExcel']);
    });

    // Escrow / User Deposit Management
    Route::group(['prefix' => 'escrow', 'before' => 'admin'], function() {
        Route::get('index', ['as' => 'backend.escrow.index', 'uses' => 'Backend\EscrowController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.escrow.index.json', 'uses' => 'Backend\EscrowController@postIndexJson']);
        Route::get('{id}', ['as' => 'backend.escrow.show', 'uses' => 'Backend\EscrowController@getView']);
    });

    // Topup Transaction Management
    Route::group(['prefix' => 'topuptransaction', 'before' => 'admin'], function() {
        Route::get('index', ['as' => 'backend.topuptransaction.index', 'uses' => 'Backend\TopupTransactionController@getIndex']);
        Route::post('indexjson', ['as' => 'backend.topuptransaction.index.json', 'uses' => 'Backend\TopupTransactionController@postIndexJson']);
        Route::get('{id}', ['as' => 'backend.topuptransaction.show', 'uses' => 'Backend\TopupTransactionController@getView']);
    });

    // -- Degree API
    Route::get('degree', array(
        'as' => 'backend.degree.fetcher',
        function () {
            $id     = request()->get('id');
            $degree = App\Models\Degree::find($id);
            if ($degree) {
                $majors = $degree->majors();
                if ($majors) {
                    return Response::json($majors->get(['id', 'name']));
                }
            }
            return Response::json([]);
        }
    ));

    Route::group(['prefix' => 'fetcher'], function () {
        Route::get('industry', array('as' => 'backend.fetcher.industry', function () {
            $id         = request()->get('id');
            $industries = App\Models\Industry::find($id);
            $data       = [];
            if ($industries) {
                $i = 0;
                foreach ($industries as $industry) {
                    $data[$i]['id']          = $industry->id;
                    $data[$i]['name']        = $industry->name;
                    $data[$i]['industries']  = $industry->industries()->get(['id', 'name']);
                    $data[$i]['professions'] = $industry->professions()->get(['id', 'name']);
                    $i++;
                }

                return Response::json($data);
            }

            return Response::json([]);
        }));

        Route::get('industry/specifics', array('as' => 'backend.fetcher.industry.industries', function () {
            $id         = request()->get('id');
            $industries = App\Models\Industry::find($id);
            $data       = [];
            if ($industries) {
                $i = 0;
                foreach ($industries as $industry) {
                    $data[$i]['id']   = $industry->id;
                    $data[$i]['name'] = $industry->name;
                    $data[$i]['data'] = $industry->industries()->get(['id', 'name']);
                    $i++;
                }

                return Response::json($data);
            }

            return Response::json([]);
        }));

        Route::get('industry/professions', array('as' => 'backend.fetcher.industry.professions', function () {
            $id       = request()->get('id');
            $refID    = request()->get('ref');
            $industry = App\Models\Industry::find($id);
            if ($industry) {
                $professions = $industry->professions();
                if ($professions) {
                    return Response::json([
                        'id'   => $industry->id,
                        'name' => $industry->name,
                        'data' => $professions->get(['id', 'name']),
                    ]);
                }
            }

            return Response::json([]);
        }));
    });
});

// Route Company Backend
include ("routes_employer.php");

// Route API
include ("routes_api.php");

// Route Frontend
include ("routes_frontend.php");
