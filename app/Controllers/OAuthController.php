<?php

namespace App\Controllers;

use \App\Libraries\OAuth;
use \OAuth2\Request;
use Codeigniter\API\ResponseTrait;
use \App\Models\UserModel;


class OAuthController extends BaseController
{
    use ResponseTrait;

    public function login()
    {
        $oauth = new OAuth();
        $request = new Request();
        $respond = $oauth->server->handleTokenRequest($request->createFromGlobals());
        $code = $respond->getStatusCode();
        $body = $respond->getResponseBody();
        return $this->respondCreated(json_decode($body), $code);
    }

    public function register()
    {
        helper('form');
        $data = [];

        $rules = [
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
        ];

        if(! $this->validate($rules)){
            return $this->fail($this->validator->getErrors());
        }else{
            $model = new UserModel();
            $data = [
                'email' => $this->request->getVar('email'),
                'password' => $this->request->getVar('password'),
            ];

            $user_id = $model->insert($data);
            $data['id'] = $user_id;
            unset($data['password']);

            return $this->respondCreated($data);
        }
    }
}
