<?php

use Firebase\JWT\JWT;

class AuthController extends ControllerBase
{
    public function login()
    {
        $parameter = $this->parserDataRequest();
        $user = new Users();
        if (!$user->getUserByEmailAndPassword($parameter)) {
            dd('Hack!');
        }
        $key = base64_decode($this->config->application->jwtSecret);
        $time = time();
        $token = [
            'iss' =>  'http://lackky.com',
            'iat' =>  $time,
            'exp' =>  $time + 8640,
            'data' =>[
                'email' => $parameter['email']
            ]

        ];
        $jwt = JWT::encode($token, $key);
        return $this->respondWithArray([
            'message' => 'Successful Login',
            'token' => $jwt
        ]);
    }

    public function check()
    {

    }

}
