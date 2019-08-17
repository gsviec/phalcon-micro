<?php
namespace App\Auth;

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

    public function beforeHandleRoute(Event $event, Micro $app)
    {
        //@TODO
    }

    /**
     * @param Event $event
     * @param Micro $app
     *
     * @return bool
     */
    public function beforeExecuteRoute(Event $event, Micro $app)
    {

        if ($this->isUnsecuredRoute($app)) {
            return true;
        }
        $authHeader= $app->request->getHeader('authorization');
        if ($authHeader) {
            $jwt = explode(" ", $authHeader);
            $key = base64_decode($app['config']->application->jwtSecret);
            if (isset($jwt[1])) {
                try {
                    $decoded = JWT::decode($jwt[1], $key, ['HS256']);
                    // Send data auth for via cookies
                    $app->cookies->set('auth', $decoded);
                } catch (\Exception $e) {
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

    /**
     * @param Micro $app
     *
     * @return bool
     */
    private function isUnsecuredRoute(Micro $app)
    {
        $unsecuredRoutes = [
            ['router' => '/auth', 'action' => 'login'],
            ['router' => '/users', 'action' => 'add']
        ];
        if ('/' == $app->getRouter()->getRewriteUri()) {
            return  true;
        }
        foreach ($unsecuredRoutes as $route) {
            if ($route['router'] == $app->getRouter()->getRewriteUri()
                && $route['action'] == $app->getActiveHandler()[1]
            ) {
                return true;
            }
        }

        return false;
    }
}
