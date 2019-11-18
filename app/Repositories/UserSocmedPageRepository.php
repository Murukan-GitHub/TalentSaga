<?php
namespace App\Repositories;

use App\Models\UserSocmedPage;
use Suitcore\Repositories\SuitRepository;

class UserSocmedPageRepository extends SuitRepository
{
    /**
     * Countructor
     */
    public function __construct()
    {
        $this->mainModel = new UserSocmedPage;
    }
}
