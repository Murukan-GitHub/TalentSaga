<?php

namespace App\Repositories;

use Suitcore\Repositories\SuitRepository;
use App\Models\NewsletterSubscriber;

class NewsletterSubscriberRepository extends SuitRepository
{
    
    public function __construct()
    {
        $this->mainModel = new NewsletterSubscriber;
    }

    /**
     * Get object detail by email
     * @param  string $name
     * @param  string $email
     * @return array Object Detail
     **/
    public function getByEmail($email)
    {
    	$object = ($this->mainModel ? $this->mainModel : NewsletterSubscriber::class);
    	$object = $object->where('email', $email)
    	           ->first();
        return $object;
    }
}
