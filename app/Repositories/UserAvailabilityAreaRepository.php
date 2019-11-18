<?php
namespace App\Repositories;

use App\Models\UserAvailabilityArea;
use Suitcore\Repositories\SuitRepository;

class UserAvailabilityAreaRepository extends SuitRepository
{
    /**
     * Countructor
     */
    public function __construct()
    {
        $this->mainModel = new UserAvailabilityArea;
    }
}
