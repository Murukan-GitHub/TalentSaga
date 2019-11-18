<?php
namespace App\Repositories;

use App\Models\UserProfile;
use Suitcore\Repositories\SuitRepository;

class UserProfileRepository extends SuitRepository
{
    /**
     * Countructor
     */
    public function __construct()
    {
        $this->mainModel = new UserProfile;
    }
}
