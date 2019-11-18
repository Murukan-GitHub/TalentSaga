<?php
namespace App\Repositories;

use App\Models\UserProfileExpertise;
use Suitcore\Repositories\SuitRepository;

class UserProfileExpertiseRepository extends SuitRepository
{
    /**
     * Countructor
     */
    public function __construct()
    {
        $this->mainModel = new UserProfileExpertise;
    }
}
