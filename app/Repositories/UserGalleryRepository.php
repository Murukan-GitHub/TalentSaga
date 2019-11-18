<?php
namespace App\Repositories;

use App\Models\UserGallery;
use Suitcore\Repositories\SuitRepository;

class UserGalleryRepository extends SuitRepository
{
    /**
     * Countructor
     */
    public function __construct()
    {
        $this->mainModel = new UserGallery;
    }
}
