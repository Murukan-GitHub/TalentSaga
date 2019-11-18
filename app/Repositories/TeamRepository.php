<?php

namespace App\Repositories;

use Cache;
use Suitcore\Repositories\SuitRepository;
use App\Models\Team;

class TeamRepository extends SuitRepository
{
    const FETCH_ALL = -1;

    public function __construct()
    {
        $this->mainModel = new Team;
    }

    public function getById($id) {
        $object = null;
        $param = [];
        $param['id'] = $id;
        $param['paginate'] = false; // single object
        $param['perPage'] = 1; // single object
        $object = $this->getByParameter($param);
        return $object;
    }

    public function getAllList() {
        $listObj = null;
        $param = [];
        $param['paginate'] = false;
        $param['perPage'] = self::FETCH_ALL;
        $listObj = $this->getByParameter($param);
        return $listObj;
    }

    public function getCachedList() {
        $baseModel = $this->mainModel;
        $faqList = Cache::rememberForever('talentsaga_team_member', function () use($baseModel) {
            return $baseModel->orderByRaw('(position_order * -1) desc')->get()->keyBy('id');
        });
        return $faqList;
    }
}
