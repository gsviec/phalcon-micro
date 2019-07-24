<?php

/**
 * Class UsersController
 */
class UsersController extends ControllerBase
{

    /**
     * @return \Phalcon\Http\ResponseInterface
     */
    public function list()
    {
        $users = Users::find();
        $config = [];
        $pagination = $this->pagination($users);
        return $this->respondWithPagination($pagination, new UsersTransformer($config));
    }

    /**
     * @param $email
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function item($email)
    {
        $config = [];
        $user = Users::findFirstByEmail($email);
        if (!$user) {
            return $this->respondWithError('The user do not exits!');
        }
        return $this->respondWithItem($user, new UsersTransformer($config));
    }
    /**
     * @return \Phalcon\Http\ResponseInterface
     */
    public function add()
    {
        $data = $this->parserDataRequest();
        if (!isset($data['email']) || !isset($data['password'])) {
            return $this->respondWithError('You need provider email and password');
        }
        $user = Users::findFirstByEmail($data['email']);
        if ($user) {
            return $this->respondWithError('That email is taken. Try another');
        }
        $data['password'] = $this->security->hash($data['password']);

        if (!$user->save($data)) {
            foreach ($user->getMessages() as $m) {
                return $this->respondWithError('Add user fall');
            }
        }
        return $this->respondWithSuccess('Add user success');
    }
    public function update()
    {
        $data = $this->parserDataRequest();
        if (!isset($data['email'])) {
            return $this->respondWithError('You need provider email');
        }
        $user = Users::findFirstByEmail($data['email']);
        if (!$user) {
            return $this->respondWithError('The user do not exits!');
        }
        unset($data['password']);

        if (!$user->save($data)) {
            foreach ($user->getMessages() as $m) {
                return $this->respondWithError('Update user fall');
            }
        }
        return $this->respondWithSuccess('Update user success');
    }
}
