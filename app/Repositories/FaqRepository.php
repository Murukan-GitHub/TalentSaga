<?php

namespace App\Repositories;

use Cache;
use App\Models\Faq;
use App\Repositories\FaqCategoryRepository;
use Suitcore\Repositories\SuitRepository;

class FaqRepository extends SuitRepository
{
    const FETCH_ALL = -1;

    protected $faqCategoryRepo;

    public function __construct(FaqCategoryRepository $_faqCategoryRepo)
    {
        $this->mainModel = new Faq;
        $this->faqCategoryRepo = $_faqCategoryRepo;
    }

    public function getCachedList($categorySlug) {
        $baseModel = $this->mainModel;
    	$faqCategory = ($categorySlug ? $this->faqCategoryRepo->getBy($categorySlug) : null);
        $faqList = [];
    	if ($faqCategory) {
	        $faqList = Cache::rememberForever('faq_by_category_'.$faqCategory->id, function () use($baseModel, $faqCategory) {
	            return $baseModel->where('faq_category_id','=',$faqCategory->id)->orderByRaw('(position_order * -1) desc')->get()->keyBy('id');
	        });
    	} else {
	        $faqList = Cache::rememberForever('faq_by_category_all', function () use($baseModel) {
	            return $baseModel->orderByRaw('(position_order * -1) desc')->get()->keyBy('id');
	        });
    	}
        return $faqList;
    }
}
