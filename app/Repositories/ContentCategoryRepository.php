<?php

namespace App\Repositories;

use App\Models\ContentCategory;
use Suitcore\Repositories\SuitRepository;

class ContentCategoryRepository extends SuitRepository
{
	protected $contentTypeRepo;

    public function __construct(ContentTypeRepository $_contentTypeRepo)
    {
        $this->mainModel = new ContentCategory;
        $this->contentTypeRepo = $_contentTypeRepo;
    }

    public function getOrInit($slug, $typeCode) {
    	$relatedType = $this->contentTypeRepo->getOrInit($typeCode);
    	$currentState = $this->mainModel->where('slug','=',$slug)
    						 ->where('type_id','=',$relatedType->id)->first();
    	if (!$currentState) {
    		$currentState = new ContentCategory;
    		$currentState->parent_id = null;
    		$currentState->type_id = $relatedType->id;
    		$currentState->name = ucfirst(strtolower($slug));
    		$currentState->slug = $slug;
    		$currentState->save();
    	}
    	return $currentState;
    }

    public function getBy($slug, $typeCode) {
    	$relatedType = $this->contentTypeRepo->getBy($typeCode);
    	$currentState = null;
    	if ($relatedType) {
    		$currentState = $this->mainModel->where('slug','=',$slug)
    						 ->where('type_id','=',$relatedType->id)->first();
		}
    	return $currentState;
    }
}
