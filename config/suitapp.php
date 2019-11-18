<?php 

return [
	'api' => [
		'google_map' => env('GOOGLE_MAP_API'),
		'facebook_app' => env('FACEBOOK_APP_ID')
	],

    'images' => [
        'imageWithThumbnail' => true,
        'imageDestinationPath' => 'public/files', // base_path based
        'imageUseAbsolutePath' => false,
        'imageFileNameOnly' => true,
        'imageBasePath' => 'public', // based_path based
        'imageDirectory' => '',
        'imageMaxHeight' => 1800, // based on retina display
        'imageMaxWidth' => 2880, // based on retina display
    ],

    'thumbnailer' => [
        // 'thumb' => '_thumb_',
        // 'size' => '300x300',
    ],

    'uploader' => [
        // 'override' => false,
        // 'modelOverride' => true,
        // 'baseFolder' => 'public/uploads',
        // 'folder' => '',
    ],

    'emailer' => [
        'from' => [
            'address' => env('EMAIL_ADDRESS', 'suitcore@suitmedia.com'),
            'name' => env('EMAIL_NAME', 'Suitcore'),
        ],
        'subject' => 'Welcome !',
        'to' => 'test@suitmedia.com',
        'siteurl' => env('SITE_URL', 'http://suitcoreinstances.suitdev.com'),
        'sitename' => env('SITE_NAME', 'Suitcore Instances'),
        'activation' => ['subject' => 'Aktifasi Akun'],
        'welcome' => [],
        'alert' => ['parent' => 'welcome', 'subject' => 'alert'],
        'invoice' => [],
    ],
];
