<?php 

namespace App\Libraries;
// use \OAuth2\Storage\Pdo;
use \App\Libraries\CustomOauthStorage;

class OAuth{
    var $server;

    function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $dsn = getenv('database.default.DSN');
        $username = getenv('database.default.username');
        $password = getenv('database.default.password');

        $storage = new CustomOauthStorage(['dsn' => $dsn, 'username' => $username, 'password' => $password]);
        $this->server = new \OAuth2\Server($storage);
        $this->server->addGrantType(new \OAuth2\GrantType\UserCredentials($storage));
    }
    public function verifyToken()
    {
        $accessToken = $this->server->getAccessTokenData(\OAuth2\Request::createFromGlobals());
        
        return $accessToken && !$this->server->getStorage('access_token')->isExpired($accessToken['access_token']);
    }
}