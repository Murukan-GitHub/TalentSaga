<?php 

namespace Suitcore\Accounts;

class FacebookAccount implements SocialAccountInterface
{
    use CommonAccountFields;

    protected function requiredPairs()
    {
        return [
            'id' => 'facebook_id',
            'token' => 'facebook_token'
        ];
    }

    public function getType()
    {
        return "facebook";
    }

    public function getIdentifierColumn()
    {
        return 'fb_id';
    }

    public function getTokenColumn()
    {
        return 'fb_access_token';
    }

    public function getSecretColumn()
    {
        return false;
    }
}
