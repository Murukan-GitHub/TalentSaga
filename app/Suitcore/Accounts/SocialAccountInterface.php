<?php 

namespace Suitcore\Accounts;

interface SocialAccountInterface {

    /**
     * Get the column name on table User that represent this account ID.
     *
     * @return String
     */
    public function getIdentifierColumn();

    public function getTokenColumn();

    public function getAccessToken();

    public function getSecretColumn();

    public function getSecret();

    public function getId();

    public function getUsername();

    public function getEmail();

    public function getName();

    public function getGender();

    public function getLocation();

    public function getBio();

    public function getPicture();

    public function getRandomPassword();

    public function getType();
}
