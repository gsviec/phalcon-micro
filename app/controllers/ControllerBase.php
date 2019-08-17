<?php
namespace App\Controllers;

use League\Fractal\Pagination\Cursor;
use League\Fractal\Pagination\PhalconFrameworkPaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Manager as FractalManager;
use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;
use Phalcon\Paginator\Adapter\NativeArray as PaginatorNativeArray;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;

/**
 * Class ControllerBase
 *
 * @package App\Controllers
 */
class ControllerBase extends Controller
{
    /**
     * @constant string name of api with the 400 client error
     * @link     http://www.restapitutorial.com/httpstatuscodes.html
     */
    const CODE_WRONG_ARGS = 'GEN-FUBARGS';

    /**
     * @constant string name of api with the 404 client error
     */
    const CODE_NOT_FOUND = 'GEN-LIKETHEWIND';

    /**
     * @constant string name of api with the 500 server error
     */
    const CODE_INTERNAL_ERROR = 'GEN-AAAGGH';

    /**
     * @constant string name of api with the 401 client error
     */
    const CODE_UNAUTHORIZED = 'GEN-MAYBGTFO';

    /**
     * @constant string name of api with the 403 client error
     */
    const CODE_FORBIDDEN = 'GEN-GTFO';

    const CODE_WRONG_DATA = 'GEN-DATA';

    /**
     *
     * @var integer
     */
    protected $statusCode = 200;
    /**
     * @var int
     */
    protected $perPage = 10;

    protected $userId = null;
    /**
     * @var null
     */
    protected $tenantId = null;
    /**
     * @var string
     */
    protected $userDateFormat = 'd/m/Y';

    protected $customField;

    /**
     * Getter for statusCode
     *
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Setter for statusCode
     *
     * @param int $statusCode Value to set
     *
     * @return self
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }


    protected function respondWithItem($item, $callback)
    {
        $resource  = new Item($item, $callback);
        $fractal   = new FractalManager();
        $rootScope = $fractal->createData($resource);

        return $this->respondWithArray($rootScope->toArray());
    }


    protected function respondWithCollection($collection, $callback)
    {
        $resource = new Collection($collection, $callback);
        $fractal = new FractalManager();
        $rootScope = $fractal->createData($resource);

        return $this->respondWithArray($rootScope->toArray());
    }


    protected function respondWithPagination($paginator, $callback)
    {
        $pagination = $paginator->getPaginate();
        $resource = new Collection($pagination->items, $callback);
        $resource->setPaginator(new PhalconFrameworkPaginatorAdapter($pagination));

        $fractal = new FractalManager();
        $rootScope = $fractal->createData($resource);
        return $this->respondWithArray($rootScope->toArray());
    }


    protected function respondWithCursor($paginator, $callback)
    {
        $pagination = $paginator->getPaginate();
        $resource = new Collection($pagination->items, $callback);
        $cursor = new Cursor(
            $pagination->current,
            $pagination->before,
            $pagination->next,
            $pagination->total_items
        );
        $resource->setCursor($cursor);
        $rootScope = $this->fractal->createData($resource);

        return $this->respondWithArray($rootScope->toArray());
    }

    /**
     * @param array $data*
     * @return \Phalcon\Http\ResponseInterface
     */
    public function respondWithArray(array $data)
    {
        $response = new Response();
        $response->setContentType('application/json', 'UTF-8');

        return $response->setContent(json_encode($data, JSON_NUMERIC_CHECK));
    }

    /**
     * @param $message
     * @param string $errorCode
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function respondWithError($message, $errorCode = '400')
    {
        //$this->logger->error($message);
        return $this->respondWithArray([
            'error' => [
                'code' => $errorCode,
                'http_code' => $this->statusCode,
                'message' => $message,
            ]
        ]);
    }

    public function respondWithSuccess($message = 'ok')
    {
        return $this->respondWithArray(
            [
                'success' => [
                    'message' => $message,
                    'code' => 202
                ]
            ]
        );
    }

    /**
     * @param string $message
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function errorForbidden($message = 'Forbidden')
    {
        return $this->setStatusCode(403)
            ->respondWithError($message, self::CODE_FORBIDDEN);
    }

    /**
     * @param string $message
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function errorInternalError($message = 'Internal Error')
    {
        return $this->setStatusCode(500)->respondWithError($message, self::CODE_INTERNAL_ERROR);
    }

    /**
     * @param string $message
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function errorNotFound($message = 'Resource Not Found')
    {
        return $this->setStatusCode(404)->respondWithError($message, self::CODE_NOT_FOUND);
    }

    /**
     * @param string $message
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function errorUnauthorized($message = 'Unauthorized')
    {

        return $this->setStatusCode(401)->respondWithError($message, self::CODE_UNAUTHORIZED);
    }

    /**
     * @param string $message
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function errorWrongArgs($message = 'Wrong Arguments')
    {
        return $this->setStatusCode(400)->respondWithError($message, self::CODE_WRONG_ARGS);
    }

    /**
     * @param string $message
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function errorWrongData($message = 'Wrong Data')
    {
        return $this->setStatusCode(409)->respondWithError($message, self::CODE_WRONG_DATA);
    }

    /**
     * @param $query
     *
     * @return PaginatorQueryBuilder
     */
    public function pagination($query)
    {
        $page = $this->request->getQuery('page') ?: 1;
        $perPage = $this->request->getQuery('limit') ?: $this->perPage;
        if (is_object($query)) {
            $paginator = new PaginatorModel([
                'data' => $query,
                'limit' => $perPage,
                'page' => $page
            ]);
        } elseif (isset($query['model'])) {
            $builder = ModelBase::modelQuery($query);
            $paginator = new PaginatorQueryBuilder(
                [
                    'builder' => $builder,
                    'limit' => $perPage,
                    'page' => $page
                ]
            );
        } else {
            $paginator = new PaginatorNativeArray([
                'data' => $query,
                'limit' => $perPage,
                'page' => $page
            ]);
        }
        return $paginator;
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function getOne($query)
    {
        $builder = ModelBase::modelQuery($query);
        return $builder
            ->getQuery()
            ->setUniqueRow(true)
            ->execute();
    }

    /**
     * @return array
     */
    public function getParameter()
    {
        $query = $this->request->getQuery();
        $query = array_filter($query, function ($val) {
            return !empty($val);
        });
        //define the fields required for a partial response.
        if (isset($query['fields'])) {
            $fields = explode(',', $query['fields']);
            $query['fields'] = $fields;
        }

        return $query;
    }

    /**
     * @return array
     */
    public function parserDataRequest()
    {
        if ($this->request->getHeader('CONTENT_TYPE') == 'application/json') {
            $posts = $this->request->getJsonRawBody(true);
        } else {
            $posts = $this->request->getRawBody(true);
        }
        if (is_array($posts) && 0 == count($posts)) {
            $posts = $this->request->getPost();
            if ($this->request->isPut()) {
                $posts = $this->request->getPut();
            }
        }
        return $posts;
    }

    /**
     * @return mixed|null
     */
    public function getCurrentUser()
    {
        if ($this->cookies->has('auth')) {
            return $this->cookies->get('auth')->getValue()->data;
        }
        return null;
    }
}
