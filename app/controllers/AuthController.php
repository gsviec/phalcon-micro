<?php

use Firebase\JWT\JWT;

class AuthController extends ControllerBase
{
    public function login()
    {
        $parameter = $this->parserDataRequest();
        $user = Users::getUserByEmailAndPassword($parameter);
        if (!$user) {
            return $this->respondWithError('Your email or password do not correct');
        }
        $key = base64_decode($this->config->application->jwtSecret);
        $time = time();
        $expiresIn = $time + 86400*365;
        $token = [
            'iss' =>  'http://lackky.com',
            'iat' =>  $time,
            'exp' =>  $expiresIn,
            'data' =>[
                'email' => $user->getEmail(),
                'id' => $user->getId()
            ]
        ];
        $jwt = JWT::encode($token, $key);
        return $this->respondWithArray([
            'message' => 'Successful Login',
            'token' => $jwt,
            'expiresIn' => $expiresIn
        ]);
    }

    public function check()
    {

    }

    public function logout()
    {
        
    }
}
