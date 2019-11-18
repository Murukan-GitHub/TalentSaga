<?php
namespace App\Repositories;

use Cache;
use Suitcore\Models\SuitModel;
use App\Models\TalentCategory;
use Suitcore\Repositories\SuitRepository;

class TalentCategoryRepository extends SuitRepository
{
    /**
     * Countructor
     */
    public function __construct()
    {
        $this->mainModel = new TalentCategory;
    }

    public function allDescendantId($category) {
        // cached
        if ($category) {
            $repoHandler = $this;
            return Cache::rememberForever('descendant_categories_of_'.$category->id, function () use($repoHandler, $category) {
                return $repoHandler->directQueryAllDescendantId($category);
            });
        } else {
            return [];
        }
    }

    public function directQueryAllDescendantId($category) {
        // non cache
        if ($category) {
            $childCategories = $category->children;
            if ($childCategories && count($childCategories) > 0) {
                $result = [ $category->id ];
                foreach ($childCategories as $child) {
                    $result = array_merge($result, $this->directQueryAllDescendantId($child));
                }
                return $result;
            } else {
                return [ $category->id ];
            }
        } else {
            return [];
        }
    }

    public function cachedList($fetchAll = false, $parentCatId = 0)
    {
        return Cache::rememberForever(($fetchAll ? 'all_categories' : 'categories_by_'.($parentCatId ? $parentCatId : 'main')), function () use($fetchAll, $parentCatId) {
            $categories = null;
            if ($fetchAll) $categories = TalentCategory::active()->orderBy('position_order','asc')->orderBy('name','asc')->get();
            elseif ($parentCatId) $categories = TalentCategory::active()->where('parent_id', '=', $parentCatId)->with('children')->orderBy('position_order','asc')->orderBy('name','asc')->get();
            else $categories = TalentCategory::active()->whereNull('parent_id')->with('children')->orderBy('position_order','asc')->orderBy('name','asc')->get();

            if ($categories && count($categories) > 0) return $categories;
            return collect([]);
        });
    }
}
