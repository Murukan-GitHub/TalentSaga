<?php

namespace App\Config;

use App\Models\Job;

class BaseConfig
{
    public static $data = [
        "metrics" => [
            "currency" => "euro",
            "weight"   => "gram",
            "length"   => "meter",
            "quantity" => "pcs"
        ],
        "paginations" => [
            "perPage" => 10,
        ],
        "pageId" => [
            'A1' => [
                'label'   => 'Dashboard',
                'route'   => 'backend.home.index',
                'icon'    => 'icon-bar-chart',
                'roles'   => ['admin'],
                'submenu' => []
            ],
            'C1' => [
                'label'   => 'User',
                'route'   => 'backend.user.index',
                'icon'    => 'icon-users',
                'roles'   => ['admin'],
                'submenu' => []
            ],
            'F1' => [
                'label'   => 'Talent Booking',
                'route'   => 'backend.userbooking.index',
                'icon'    => 'icon-drawer',
                'roles'   => ['admin'],
                'submenu' => []
            ],
            'G1' => [
                'label'   => 'User Stories',
                'route'   => 'backend.userstory.index',
                'icon'    => 'icon-drawer',
                'roles'   => ['admin'],
                'submenu' => []
            ],
            'B' => [
                'label'   => 'Marketing',
                'route'   => '',
                'icon'    => 'icon-briefcase',
                'roles'   => ['admin'],
                'submenu' => [
                    'B1'  => [
                        'label' => 'Email Subscriber',
                        'route' => 'backend.newslettersubscribers.index',
                        'icon'  => 'fa fa-user',
                        'roles' => ['admin']
                    ],
                    'B2'  => [
                        'label' => 'Newsletter',
                        'route' => 'backend.newsletter.index',
                        'icon'  => 'fa fa-envelope',
                        'roles' => ['admin']
                    ]
                ]
            ],
            'D' => [
                'label'   => 'Master Data',
                'route'   => '',
                'icon'    => 'icon-drawer',
                'roles'   => ['admin'],
                'submenu' => [
                    'D1' => [
                        'label' => 'Country',
                        'route' => 'backend.country.index',
                        'icon'  => 'fa fa-globe',
                        'roles' => ['admin'],
                    ],
                    'D3' => [
                        'label' => 'City',
                        'route' => 'backend.city.index',
                        'icon'  => 'fa fa-map',
                        'roles' => ['admin'],
                    ],
                    'D4' => [
                        'label' => 'Talent Category',
                        'route' => 'backend.talentcategory.index',
                        'icon'  => 'fa fa-map',
                        'roles' => ['admin'],
                    ],
                    'D5' => [
                        'label' => 'Talent Expertise',
                        'route' => 'backend.talentexpertise.index',
                        'icon'  => 'fa fa-map',
                        'roles' => ['admin'],
                    ],
                    'D6' => [
                        'label' => 'Price Inclusion',
                        'route' => 'backend.priceinclusion.index',
                        'icon'  => 'fa fa-map',
                        'roles' => ['admin'],
                    ],
                    'D7' => [
                        'label' => 'Currency',
                        'route' => 'backend.currency.index',
                        'icon'  => 'fa fa-globe',
                        'roles' => ['admin'],
                    ]
                ]
            ],
            'E' => [
                'label'   => 'Site',
                'route'   => '',
                'icon'    => 'icon-globe',
                'roles'   => ['admin'],
                'submenu' => [
                    'E1' => [
                        'label' => 'Type & Category',
                        'route' => 'backend.contenttype.index',
                        'icon'  => 'icon-tag',
                        'roles' => ['admin'],
                    ],
                    'E2' => [
                        'label' => 'Content',
                        'route' => 'backend.content.index',
                        'icon'  => 'icon-note',
                        'roles' => ['admin'],
                    ],
                    'E3' => [
                        'label' => 'Team',
                        'route' => 'backend.team.index',
                        'icon'  => 'icon-users',
                        'roles' => ['admin'],
                    ],
                    'E4' => [
                        'label' => 'Contact Message',
                        'route' => 'backend.contactmessage.index',
                        'icon'  => 'fa fa-comment-o',
                        'roles' => ['admin'],
                    ],
                    'E5' => [
                        'label' => 'FAQ',
                        'route' => 'backend.faq.index',
                        'icon'  => 'icon-question',
                        'roles' => ['admin'],
                    ],
                    'E6' => [
                        'label' => 'Setting',
                        'route' => 'backend.settings.view',
                        'icon'  => 'icon-settings',
                        'roles' => ['admin'],
                    ]
                ]
            ]
        ]
    ];
}
