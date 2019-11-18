<?php

namespace Suitcore\Accounts;

class GoogleAccount implements SocialAccountInterface
{
    use CommonAccountFields;

    protected function requiredPairs()
    {
        return [
            'id' => 'google_id',
            'token' => 'google_token'
        ];
    }

    public function getType()
    {
        return "google";
    }

    public function getIdentifierColumn()
    {
        return 'gp_id';
    }

    public function getTokenColumn()
    {
        return 'gp_access_token';
    }

    public function getSecretColumn()
    {
        return false;
    }
}
