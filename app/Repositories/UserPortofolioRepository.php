<?php
namespace App\Repositories;

use App\Models\UserPortofolio;
use Suitcore\Repositories\SuitRepository;

class UserPortofolioRepository extends SuitRepository
{
    /**
     * Countructor
     */
    public function __construct()
    {
        $this->mainModel = new UserPortofolio;
    }
}
