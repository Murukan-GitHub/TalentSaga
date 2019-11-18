<?php

namespace Suitcore\Accounts;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

trait CommonAccountFields
{
    private $id;

    private $data;

    private $token = null;

    private $secret = null;

    private $randomPassword;

    public function __construct($id, $data = [], $token = null, $secret = null)
    {
        if ($id instanceof Request) {
            $this->fillData($id);
        } else {
            $this->id = $id;
            $this->data = $data;
            $this->token = $token;
            $this->secret = $secret;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAccessToken()
    {
        return $this->token;
    }

    public function getSecret()
    {
        return $this->secret;
    }

    public function getUsername()
    {
        return $this->getData('id');
    }

    public function getEmail()
    {
        return $this->getData('email');
    }

    public function getName()
    {
        return $this->getData('name');
    }

    public function getGender()
    {
        return $this->getData('gender');
    }

    public function getLocation()
    {
        return $this->getData('location');
    }

    public function getBio()
    {
        return $this->getData('bio');
    }

    public function getPicture()
    {
        return $this->getData('avatar');
    }

    public function getRandomPassword()
    {
        if ($this->randomPassword == null || empty(trim($this->randomPassword))) {
            $this->randomPassword = str_random(5);
        }

        return $this->randomPassword;
    }

    private function getData($key, $default = false)
    {
        return Arr::get($this->data, $key, $default);
    }

    private function fillData(Request $request)
    {
        $columns = $this->requiredPairs();
        $required = array_values($columns);

        if (! $request->has(array_merge($required, ['name', 'email']))) {
            throw new \InvalidArgumentException('Failed to login, some mandatory fields not set.');
        }

        foreach (array_filter($columns) as $field => $column) {
            $this->$field = $request->get($column);
        }

        $this->data = $request->except($required);
    }
}
