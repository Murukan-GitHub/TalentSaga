<?php

namespace Suitcore\Accounts;

class LinkedInAccount implements SocialAccountInterface
{
    use CommonAccountFields;

    protected function requiredPairs()
    {
        return [
            'token' => 'linkedin_token'
        ];
    }

    public function getType()
    {
        return "linkedin";
    }

    public function getIdentifierColumn()
    {
        return false;
    }

    public function getTokenColumn()
    {
        return 'li_access_token';
    }

    public function getSecretColumn()
    {
        return false;
    }
}
