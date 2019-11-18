# TalentSage #

## Description ##
Talentsaga is web application that support talent offers and bookings. Suitcore is standard application core to build an application based on client requirement. This PHP version build on top of Laravel Framework with PDO database interfaces.

Version : 1.0_php


## Official Documentation ##
Documentation for the framework can be found on the [Google docs](https://docs.google.com).


## Database Table / Model ##
users
=====
- id
- picture
- username
- email
- password
- name
- last_name
- phone_number
- birthdate
- remember_token
- forget_password_token
- escrow_amount
- role
- status
- registration_date
- last_visit
- rating ---------------
- created_at
- updated_at

countries
=========
- id
- code
- name
- created_at
- updated_at

cities
======
- id
- country_id
- code
- name
- created_at
- updated_at

talent_categories
=================
- id 
- parent_id --> talent_categories
- position_order
- slug
- name
- description
- cover_image
- created_at
- updated_at

talent_expertises
=================
- id
- talent_category_id --> talent_categories
- position_order
- slug
- name
- is_curated : true, false
- created_at
- updated_at

price_inclusions
================
- id
- position_order INT(10) NULL
- name
- is_curated : true, false
- created_at
- updated_at

user_profiles
=============
- id
- user_id --> users
- talent_category_id -> talent_categories
- talent_profession
- talent_expertise_id -> talent_expertises
- talent_description
- price_estimation
- price_notes
- contact_for_price : true, false
- country_id --> countries
- city_id --> cities
- street_name
- street_number
- zip_code
- gender
- weight
- height
- facebook_page
- twitter_page
- instagram_page
- youtube_page
- status ('draft', 'published')
- created_at
- updated_at

user_price_inclusions
=====================
- id
- user_id
- price_inclusion_id
- created_at
- updated_at

user_availability_areas
=======================
- id
- user_id
- country_id
- created_at
- updated_at

user_portofolios
================
- id
- user_id
- event_date
- event_name
- description
- url
- youtube_url
- status ('draft', 'published')
- created_at
- updated_at

user_galleries
==============
- id
- user_id
- type ('image','video')
- title NULLABLE
- cover_url
- image_media_url
- external_media_url
- status ('draft', 'published')
- created_at
- updated_at

user_bookings
=============
- id
- user_id
- talent_user_id
- event_title
- event_detail
- event_date
- event_start_time
- event_end_time
- location (string)
- status : created, approved, rejected, canceled, done
- talent_rate (integer)
- talent_review_date (datetime)
- talent_review (string)
- created_at (datetime)
- updated_At (datetime)

user_stories
============
- id
- user_id
- title
- highlight
- cover_image
- content
- status ('draft', 'published')
- created_at
- updated_at

contact_messages
================
- id INT(10) NOT NULL
- sender_name VARCHAR(100)
- sender_email VARCHAR(255) NOT NULL
- subject VARCHAR(48) NOT NULL -----------------------
- content VARCHAR(1024) NOT NULL
- reply VARCHAR(1024) NULLABLE
- status VARCHAR(50) // options : 'created', 'replied'
- created_at TIMESTAMP
- updated_at TIMESTAMP

faq_categories
==============
- id INT(10) NOT NULL
- position_order INT(10) NULL
- name varchar(255) NOT NULL
- slug varchar(255)
- created_at TIMESTAMP
- updated_at TIMESTAMP

faqs
====
- id INT(10) NOT NULL
- faq_category_id INT(10) NULL
- question VARCHAR(1024) NOT NULL
- answer TEXT NOT NULL
- created_at TIMESTAMP
- updated_at TIMESTAMP

content_types
=============
- id INT(10) NOT NULL
- name varchar(255) NOT NULL
- code varchar(32) NOT NULL
- created_at DATETIME NOT NULL
- updated_at DATETIME NOT NULL

content_categories
==================
- id INT(10) NOT NULL
- parent_id INT(10) NOT NULL
- type_id INT(10) NOT NULL
- name varchar(255) NOT NULL
- slug varchar(255) NOT NULL
- created_at DATETIME NOT NULL
- updated_at DATETIME NOT NULL

contents
========
- id INT(10) NOT NULL
- type_id INT(10) NULL
- category_id INT(10) NULL
- title varchar(64) NOT NULL
- slug varchar(100) NOT NULL
- highlight varchar(512) NULL
- content TEXT NOT NULL
- image TEXT NULL
- attachment_file TEXT NULL
- status varchar(50) NOT NULL
- created_at DATETIME NOT NULL
- updated_at DATETIME NOT NULL

newsletter_subscribers
======================
- id
- name
- email
- created_at
- updated_at


## Basic Standard Operation Procedure (SOP) ##
* Don't change Suitcore folder/files content.
* All controller must use related/needed injected repository instead of make direct query builder to model's object instances.
* BackendController already have standard action (CRUD) : datatable list, create, update, delete and detail. You can gain it by duplicate backend view from views/backend/default, extend and adapt base BackendController config.
* If client have new module, make new related model (extends SuitModel, don't forget to define attributeSettings) on app/Models, related repository (extends SuitRepository) that use model in app/Repository, related backend controller (extends BackendController) in app/Http/Controllers/Backend and related duplicated view from views/backend/default to views/backend/[modulename]. Then set up backend controller config.
* Any interesting module in point 4 that could be refactored/included to base suitcore will be discussed later where changes made on Suitcore repository not SuitcoreApplicationInstances repository. After changes pushed to remote repository and pulled to local repository, developer can replace all Suitcore folder content in SuitcoreApplicationInstances from Suitcore folder in Suitcore base local repository.


## Implementation & Folder Structure ##
### Step(s) ###
* Clone Suitcore if you don't have this repository on your local web root folder (example /var/www)
* Clone newly created SuitcoreApplicationInstances empty repository to your local web root folder
* Copy Suitcore content (include file .gitignore, exclude folder .git) to SuitcoreApplicationInstances
* Create database needed
* Copy .env.example to .env and update needed base configuration
* Run composer install
* Run php artisan migrate
* Run php -d memory_limit=2G artisan db:seed
* Edit readme.md to make clear summary of project descripiton
* Push all code to by run : git commit -m "initial instances code" & git push origin master
* Now you can access from : http://localhost/SuitcoreApplicationInstances/public
* You can setting apache / nginx virtual host to make access more comfortably (example http://suitcoreapplicationinstances.dev where that domain access folder /var/www/SuitcoreApplicationInstances/public for example) and further use of mobile subdomain if needed (m.suitcoreapplicationinstances.dev as another server name) and API subdomain if needed (api.suitcoreapplicationinstances.dev)
* Frontend developer clone SuitcoreApplicationInstances, empty folder _frontend if any and put all frontend code to _frontend, then push to repository
* Backend developer pull that frontend code (git pull origin master)
* Backend developer copy / softlink content of _frontend/assets to public/frontend
* Backend developer start to implement base frontend layout (resources/views/frontend/layouts/base.blade.php) based on _frontend folder and then adapt each page in _frontend folder to related suitcore module as a new action/controller in app/Http/Controllers/Frontend with related view in resources/views/frontend, and begin developing time, happy coding :)

### Folder Structure(s) ###
```
CLIENT_APPS extends SUITCORE
|- .git : Folder repository
|- _frontend : Frontend team placed their codes here
|- app
|  |- Config
|  |   |- BaseConfig.php : Change based on client requests
|  |- Console
|  |- Events
|  |- Exceptions
|  |- Helpers
|  |- Http
|  |   |- Controllers
|  |   |   |- Auth
|  |   |   |- Api : All class must extends Suitcore\Controllers\ApiController,
|  |   |   |          Initially contain base frontend controller
|  |   |   |- Backend : All class must extends suitcore\Controllers\BackendController,
|  |   |   |                   Initially contain base frontend controller
|  |   |   |- Frontend : Initially contain base frontend controller
|  |   |   |- Mobile : Initially contain base mobile controller
|  |   |- Middleware
|  |   |- Requests
|  |   |- routes.php : Backend / admin route
|  |   |- routes_api.php : API route
|  |   |- routes_frontend.php : Frontend desktop web route (responsive / not responsive layout)
|  |   |- routes_mobile.php : Frontend mobile web route
|  |- Jobs
|  |- Listeners
|  |- Models : New model placed here and extend Suitcore\Models\SuitModel
|  |- Policies
|  |- Providers : Any service provider if needed
|  |- Repositories : New repository related to new Model on App\Model should placed here and
|  |                         extends Suitcore\Repositories\SuitRepository
|  |- Suitcore : Suitcore module, handling basic model/controller/repository and also
|                datatable representation,
|                image thumbnail handler, excel and other generic feature as a trait
|- bootstrap
|- config
|- database
|  |- factories
|  |- migrations : Base  database migration placed here
|  |- seeds : Base database data seeding placed here
|- deploy : Capistrano deploying config, changes based on staging and production server settings,
|                ignored if using deployer
|- log : Laravel log to inspect whats happen if something occured
|- public
|  |- backend : This folder contain basic themes for backend admin panel
|  |  |- css
|  |  |- fonts
|  |  |- img
|  |  |- js
|  |- files : Uploaded file on runtime placed here,in capistrano / deployer system this folder is shared area
|  |            for every deployed apps in server, excluded from versioning (git)
|  |- frontend : This folder contain themes for frontend website. This folder should be the same content with
|  |  |               _frontend\assets, you can make it softlink (ln -s command in unix based system)
|  |  |               if you know what you are doing
|  |  |- css
|  |  |- fonts
|  |  |- img
|  |  |- js
|  |- mobile : This folder contain themes for mobile website. This folder should be the same content with
|     |             _frontend\mobile\assets, you can make it softlink (ln -s command in unix based system)
|     |               if you know what you are doing
|     |- css
|     |- fonts
|     |- img
|     |- js
|- resources
|  |- lang : For label translation used in backend/frontend/mobile UI/UX, based on languages (en, id, etc)
|  |- views
|     |- backend
|     |  |- layouts : Base layout of backend view, every backend view will extends these layout
|     |  |- partials : Partial layout of backend view element, related to model if the context is adapter design pattern
|     |  |                 where will be showed in grid, list, etc
|     |  |- admin : Backend view blade layout, default generated from cloned Suitcore Repository,
|     |  |  |            changes/adapted if needed
|     |  |  |- default : Default flexible view blade layout (based on SuitModel and its child class attribute settings),
|     |  |                   you can clone new module view blade layout frome here easyly
|     |  |                   where contains standard list (with datatable), create, update, detail (with commented
|     |  |                   related object tab pane).
|     |  |- [any panel view outside admin if needed, such as partner admin, etc]
|     |- emails : Email templated used in system
|     |- errors : Error page layout (Error 404, 50x, etc. Change / adapt if needed)
|     |- frontend
|     |  |- layouts : Base layout of frontend view, every frontend view will extends these layout
|     |  |- partials : Partial layout of frontend view element, related to model if the context is adapter design pattern
|     |                    where will be showed in grid, list, etc
|     |- mobile
|        |- layouts : Base layout of mobile view, every mobile view will extends these layout
|        |- partials : Partial layout of mobile view element, related to model if the context is adapter design pattern
|                          where will be showed in grid, list, etc
|- storage : Used by laravel runtime
|- tests : Playground if you want to trying something nasty :)
|- vendor : Vendor folder that be used by packages that downloaded from library repository using composer,
|                excluded from versioning (git)
|- .env : Environmet variable that used as global main settings (environment type, database, api key, etc),
|            excluded from versioning (git)
|- .env.example : Example of .env, included in versioning (git)
|- deploy.php : Deployer script (for deployment)
|- Capfile : Capistrano config
|- readme.md : Project readme / brief
|- .gitignore : File or folder path that must excluded from versioning (git)
```

## Suitcore ##
### SuitModel ###
#### Image Thumbnail ####
**SuitModel** has 10 default size of thumbnail:
* small_square = '128x128'
* medium_square = '256x256'
* large_square = '512x512'
* xlarge_square = '2048x2048'
* small_cover = '240x_'
* medium_cover = '480x_'
* large_cover = '1280x_'
* small_banner = '_x240'
* medium_banner = '_x480'
* large_banner = '_x1280'

You can create custom thumbnail in a model by extend **SuitModel** and set the `extendedThumbnailStyle` variable:

```php
class SomeModel extends SuitModel
{
    protected $extendedThumbnailStyle = [
        'image_detail_small' => '59x59',
        'image_detail_medium' => '450x530',
        'image_detail_big' => '500x500',
        'image_preview_small' => '175x175',
        'image_preview_medium' => '300x300'
    ];
}
```

### SuitRepository ###
### SuitController ###
### Helper ###


## Security Vulnerabilities ##
If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

If you discover a security vulnerability within SuitCore, please send an e-mail to Suitcore Developer at suitcore@gmail.com. All security vulnerabilities will be promptly addressed.


## License ##
The Laravel framework and Suitcore Base Application is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
