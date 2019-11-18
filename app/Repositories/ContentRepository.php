<?php

namespace App\Repositories;

use App\Models\Content;
use Suitcore\Repositories\SuitRepository;

class ContentRepository extends SuitRepository
{
    const FETCH_ALL = -1;

    protected $contentTypeRepo;
    protected $contentCategoryRepo;

    public function __construct(ContentTypeRepository $_contentTypeRepo, ContentCategoryRepository $_contentCategoryRepo)
    {
        $this->mainModel = new Content;
        $this->contentTypeRepo = $_contentTypeRepo;
        $this->contentCategoryRepo = $_contentCategoryRepo;
    }

    public function getOrInit($slug, $typeCode = null, $categorySlug = null) {
        $relatedType = (!empty($typeCode) ? $this->contentTypeRepo->getOrInit($typeCode) : null);
        $relatedCategory = null;
        if ($relatedType) {
            $relatedCategory = (!empty($categorySlug) ? $this->contentCategoryRepo->getOrInit($categorySlug, $relatedType->code) : null);
        }
        $currentState = $this->mainModel->where('slug','=',$slug);
        if ($relatedType) {
            $currentState = $currentState->where('type_id','=',$relatedType->id);
        }
        if ($relatedCategory) {
            $currentState = $currentState->where('category_id','=',$relatedCategory->id);
        }
        $currentState = $currentState->first();
        if (!$currentState) {
            $currentState = new Content;
            $currentState->type_id = ($relatedType ? $relatedType->id : null);
            $currentState->category_id = ($relatedCategory ? $relatedCategory->id : null);
            $currentState->title = ucfirst(strtolower($slug));
            $currentState->slug = $slug;
            $currentState->highlight = "No Content Yet";
            $currentState->content = "No Content Yet";
            $currentState->image = null;
            $currentState->status = Content::PUBLISHED_STATUS;
            $currentState->save();
        }
        return $currentState;
    }

    public function getBy($slug, $typeCode) {
        $object = null;
        $param = [];
        $param['_type_code'] = $typeCode;
        $param['slug'] = $slug;
        $param['status'] = Content::PUBLISHED_STATUS;
        $param['paginate'] = false; // single object
        $param['perPage'] = 1; // single object
        $object = $this->getByParameter($param);
        return $object;
    }

    public function getListOf($typeCode, $categorySlug = null, $pagination = true, $perPage = 9) {
        $listObj = null;
        $param = [];
        $param['_type_code'] = $typeCode;
        $param['_category_slug'] = ($categorySlug ? $categorySlug : false);
        $param['status'] = Content::PUBLISHED_STATUS;
        $param['paginate'] = $pagination;
        $param['perPage'] = $perPage;
        $listObj = $this->getByParameter($param);
        return $listObj;
    }
}
