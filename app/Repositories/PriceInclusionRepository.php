<?php
namespace App\Repositories;

use App\Models\PriceInclusion;
use Suitcore\Repositories\SuitRepository;

class PriceInclusionRepository extends SuitRepository
{
    /**
     * Countructor
     */
    public function __construct()
    {
        $this->mainModel = new PriceInclusion;
    }
}
