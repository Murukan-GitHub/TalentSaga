<?php

namespace App\Repositories;

use App\Models\ContentType;
use Suitcore\Repositories\SuitRepository;

class ContentTypeRepository extends SuitRepository
{
    public function __construct()
    {
        $this->mainModel = new ContentType;
    }

    public function getOrInit($code) {
    	$currentState = $this->mainModel->where('code','=',$code)->first();
    	if (!$currentState) {
    		$currentState = new ContentType;
    		$currentState->name = ucfirst(strtolower($code));
    		$currentState->code = $code;
    		$currentState->save();
    	}
    	return $currentState;
    }

    public function getBy($code) {
    	$currentState = $this->mainModel->where('code','=',$code)->first();
    	return $currentState;
    }
}
