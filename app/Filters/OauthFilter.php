<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

use \App\Libraries\OAuth;
use \OAuth2\Request;
use \OAuth2\Response;


class OauthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $oauth = new Oauth();
        $oauthRequest = Request::createFromGlobals();
        $oauthResponse = new Response();

        if (!$oauth->server->verifyResourceRequest($oauthRequest)) {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
            header('Access-Control-Allow-Headers: Content-Type, Authorization, API-KEY, Origin, X-Requested-With, Accept, Access-Control-Request-Method');
            
            $oauth->server->getResponse()->send();
            die();
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after processing the request
    }
}
