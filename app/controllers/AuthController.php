<?php

use Firebase\JWT\JWT;

class AuthController extends ControllerBase
{
    public function login()
    {
        $parameter = $this->parserDataRequest();
        $user = Users::getUserByEmailAndPassword($parameter);
        if (!$user) {
            dd('Hack!');
        }
        $key = base64_decode($this->config->application->jwtSecret);
        $time = time();
        $token = [
            'iss' =>  'http://lackky.com',
            'iat' =>  $time,
            'exp' =>  $time + 86400,
            'data' =>[
                'email' => $user->getEmail(),
                'id' => $user->getId()
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

    public function logout()
    {
        
    }

}
