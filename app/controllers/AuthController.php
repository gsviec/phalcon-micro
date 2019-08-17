<?php
namespace App\Controllers;

use App\Models\Services\User;
use App\Models\Users;
use Firebase\JWT\JWT;

class AuthController extends ControllerBase
{
    /**
     * @var Users
     */
    protected $user;

    /**
     * @return \Phalcon\Http\ResponseInterface
     */
    public function login()
    {
        $parameter = $this->parserDataRequest();
        if (!isset($parameter['emailOrUsername'])) {
            return $this->respondWithError('You need provider email and password');
        }

        $userService = $this->getDI()->getShared(User::class);
        $this->user = $userService->findFirstByEmailOrUsername($parameter['emailOrUsername']);
        if (!$this->user) {
            return $this->respondWithError('This username or email do not exist');
        }
        if (!$this->security->checkHash($parameter['password'], $this->user->getPassword())) {
            return $this->respondWithError('Wrong password combination');
        }
        $key = base64_decode($this->config->application->jwtSecret);
        $time = time();
        $expiresIn = $time + env('EXPIRES_TOKEN');
        $token = [
            'iss' =>  $this->request->getURI(),
            'iat' =>  $time,
            'exp' =>  $expiresIn,
            'data' =>[
                'id' => $this->user->getId(),
                'email' => $this->user->getEmail(),
                'username' => $this->user->getUsername(),
            ]
        ];
        $jwt = JWT::encode($token, $key);
        return $this->respondWithArray([
            'message' => 'Successful Login',
            'token' => $jwt,
            'expiresIn' => $expiresIn
        ]);
    }

    public function logout()
    {
    }
}
