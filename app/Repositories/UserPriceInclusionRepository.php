<?php
namespace App\Repositories;

use App\Models\UserPriceInclusion;
use Suitcore\Repositories\SuitRepository;

class UserPriceInclusionRepository extends SuitRepository
{
    /**
     * Countructor
     */
    public function __construct()
    {
        $this->mainModel = new UserPriceInclusion;
    }
}
