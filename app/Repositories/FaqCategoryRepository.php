<?php

namespace App\Repositories;

use Cache;
use App\Models\FaqCategory;
use Suitcore\Repositories\SuitRepository;

class FaqCategoryRepository extends SuitRepository
{
    public function __construct()
    {
        $this->mainModel = new FaqCategory;
    }

    public function getOrInit($slug) {
    	$currentState = $this->getBy($slug);
    	if (!$currentState) {
    		$currentState = new FaqCategory;
    		$currentState->position_order = 0;
    		$currentState->name = ucfirst(strtolower($slug));
    		$currentState->slug = $slug;
    		$currentState->save();
    	}
    	return $currentState;
    }

    public function getBy($slug) {
        $currentState = $this->getCachedList();
        return (isset($currentState[$slug]) ? $currentState[$slug] : null);
    }

    public function getFirst() {
        $currentState = $this->getCachedList();
        return $currentState->first();
    }

    public function getCachedList() {
        $faqCategoryList = [];
        $baseModel = $this->mainModel;
        $faqCategoryList = Cache::rememberForever('faq_categories', function () use($baseModel) {
            return $baseModel->orderBy('position_order','asc')->orderBy('name','asc')->get()->keyBy('slug');
        });
        return $faqCategoryList;
    }
}
