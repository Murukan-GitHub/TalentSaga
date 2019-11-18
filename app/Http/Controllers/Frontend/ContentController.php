<?php

namespace App\Http\Controllers\Frontend;

use View;
use Redirect;
use App\Models\Content;
use App\Repositories\ContentRepository;
use App\Repositories\ContentTypeRepository;
use App\Repositories\ContentCategoryRepository;
use App\Repositories\TeamRepository;

class ContentController extends BaseController
{
    /** CONSTANTS **/
    const TYPE_STATIC = 'static';
    const TYPE_BLOG = 'blog';
    const STATIC_ABOUT = 'about';
    const STATIC_TERM = 'term';
    const STATIC_POLICY = 'policy';

    /** ATTRIBUTES **/
    protected $contentRepo;
    protected $teamRepo;
    protected $contentTypeRepo;
    protected $contentCategoryRepo;
    public $staticSlug = [self::STATIC_POLICY,
                             self::STATIC_TERM,
                             self::STATIC_ABOUT, ];

    /** METHODS / ACTIONS **/
    /**
     * Default constructor.
     **/
    public function __construct(ContentRepository $_contentRepo, ContentTypeRepository $_contentTypeRepo, ContentCategoryRepository $_contentCategoryRepo, TeamRepository $_teamRepo)
    {
        $this->contentRepo = $_contentRepo;
        $this->contentTypeRepo = $_contentTypeRepo;
        $this->contentCategoryRepo = $_contentCategoryRepo;
        $this->teamRepo = $_teamRepo;
    }

    /**
     * Show Static Content Detail.
     *
     * @return View|Redirect
     **/
    public function getStaticContent($slug)
    {
        if (in_array($slug, $this->staticSlug)) {
            $content = $this->contentRepo->getOrInit($slug, self::TYPE_STATIC);
            view()->share(['pageTitle' => $content->title]);

            $contentMedia = null;
            if ($slug == self::STATIC_ABOUT) {
                $contentMedia['title'] = "TEAM";
                $teamMember = $this->teamRepo->getCachedList();
                foreach ($teamMember as $key => $member) {
                    $contentMedia['media'][] = [
                        'thumbnail_image_url' => $member->avatar_large_square,
                        'title' => $member->name,
                        'subtitle' => $member->position__trans
                    ];
                }
            }
            return view('frontend.content.detail', compact('content', 'contentMedia'));
        }

        return redirect()->route('frontend.home');
    }

    /**
     * Show About Us Pages
     *
     * @return View
     **/
    public function getAboutUs()
    {
        return $this->getStaticContent(self::STATIC_ABOUT);
    }

    /**
     * Show Term of Use Pages
     *
     * @return View
     **/
    public function getTerms()
    {
        return $this->getStaticContent(self::STATIC_TERM);
    }

    /**
     * Show Policy Privacy Pages
     *
     * @return View
     **/
    public function getPolicy()
    {
        return $this->getStaticContent(self::STATIC_POLICY);
    }

    /**
     * Show Blogs List/Detail Pages.
     *
     * @return View|Redirect
     **/
    public function getBlogs($slug = null)
    {
        $contentType = $this->contentTypeRepo->getOrInit(self::TYPE_BLOG);
        view()->share(['pageTitle' => 'Blogs']);
        if ($slug) {
            $object = $this->contentRepo->getBy($slug, $contentType->code);
            if ($object) {
                return view('frontend.content.detail')->with('content', $object);
            }

            return redirect()->route('frontend.home');
        } else {
            $objectList = $this->contentRepo->getListOf($contentType->code);

            return view('frontend.content.list')
                    ->with('title', 'Info')
                    ->with('routeNode', 'blog')
                    ->with('contents', $objectList);
        }
    }
}
