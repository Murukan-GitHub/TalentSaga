<?php 

namespace Suitcore\Accounts;

class TwitterAccount implements SocialAccountInterface
{
    use CommonAccountFields;

    protected function requiredPairs()
    {
        return [
            'id' => 'twitter_id',
            'token' => 'twitter_token',
            'secret' => 'twitter_secret'
        ];
    }

    public function getType()
    {
        return "twitter";
    }

    public function getIdentifierColumn()
    {
        return 'twitter_id';
    }

    public function getTokenColumn()
    {
        return 'twitter_access_token';
    }

    public function getSecretColumn()
    {
        return 'twitter_secret';
    }
}
