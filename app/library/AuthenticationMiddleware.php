<?php

use Firebase\JWT\JWT;
use Phalcon\Mvc\Micro;
use Phalcon\Events\Event;
use Phalcon\Mvc\Micro\MiddlewareInterface;

/**
 * Class AuthenticationMiddleware
 *
 */
class AuthenticationMiddleware implements MiddlewareInterface
{
    /**
     * @param Event $event
     * @param Micro $app
     *
     * @return bool
     */
    public function beforeHandleRoute(Event $event, Micro $app)
    {
        $action = ['add'];
        if (in_array($app->getActiveHandler()[1], $action)) {
            return true;
        }
        $authHeader= $app->request->getHeader('authorization');
        if ($authHeader) {
            $jwt = explode(" ", $authHeader);
            $key = base64_decode($app['config']->application->jwtSecret);
            if (isset($jwt[1])) {
                try {
                    $decoded = JWT::decode($jwt[1], $key, ['HS256']);
                } catch (Exception $e) {
                    header('HTTP/1.0 401 Unauthorized');
                    die('Unauthorized');
                }
            }
        } else {
            //The request lacks the authorization token
            header('HTTP/1.0 400 Bad Request');
            die('Token not found in request');
        }
    }

    /**
     * Call me
     *
     * @param Micro $api
     *
     * @return bool
     */
    public function call(Micro $api)
    {
        return true;
    }
}
