<?php

namespace Southaxis\RegiCare;

use Graze\GuzzleHttp\JsonRpc\Client as RPCCLient;

abstract class Client
{

    protected $token;
    protected $domain;
    protected $client;
    protected $loginkey;

    public function __construct($domain, $token)
    {
        $this->client = RPCCLient::factory($domain, [
            'timeout' => 100,
            'verify' => false
        ]);
    }

    /**
     * @return mixed
     */
    protected function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param mixed $domain
     */
    protected function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * @return mixed
     */
    protected function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    protected function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return string $loginKey
     */
    public function getLoginkey()
    {
        return $this->loginkey;
    }

    /**
     * @param string $loginkey
     */
    public function setLoginkey($loginkey)
    {
        $this->loginkey = $loginkey;
    }
}
