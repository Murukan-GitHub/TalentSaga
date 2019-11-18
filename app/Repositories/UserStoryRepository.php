<?php
namespace App\Repositories;

use App\Models\UserStory;
use Suitcore\Repositories\SuitRepository;

class UserStoryRepository extends SuitRepository
{
    /**
     * Countructor
     */
    public function __construct()
    {
        $this->mainModel = new UserStory;
    }
}
