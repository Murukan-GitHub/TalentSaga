<?php
namespace App\Repositories;

use App\Models\TalentExpertise;
use Suitcore\Repositories\SuitRepository;

class TalentExpertiseRepository extends SuitRepository
{
    /**
     * Countructor
     */
    public function __construct()
    {
        $this->mainModel = new TalentExpertise;
    }
}
