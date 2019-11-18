<?php
namespace App\Repositories;

use App\Models\Country;
use Suitcore\Repositories\SuitRepository;

class CountryRepository extends SuitRepository
{
    /**
     * Countructor
     */
    public function __construct()
    {
        $this->mainModel = new Country;
    }
}
