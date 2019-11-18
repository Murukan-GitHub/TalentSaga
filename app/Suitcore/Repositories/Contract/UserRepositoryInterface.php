<?php 

namespace Suitcore\Repositories\Contract;

use Suitcore\Accounts\SocialAccountInterface;

interface UserRepositoryInterface
{

    public function findByIdentifier($identifier);

    public function byIdentifier($identifier);

    public function createFromSocialAccount(SocialAccountInterface $account);
}
