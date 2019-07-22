<?php

class UsersController extends ControllerBase
{
    public function index()
    {
        var_dump('expression');
    }

    public function list()
    {
        $users = Users::find();
        $config = [];
        $pagination = $this->pagination($users);
        return $this->respondWithPagination($pagination, new UsersTransformer($config));
    }
    public function add()
    {
        $data = $this->parserDataRequest();
        if (!isset($data['email'])) {
            return $this->respondWithError('You need provider email');
        }
        $user = Users::findFirstByEmail($data['email']);
        if (!$user) {
            $user = new Users();
            $data['password'] = $this->security->hash($data['password']);
        }

        if (!$user->save($data)) {
            foreach ($user->getMessages() as $m) {
                return $this->respondWithError('Add user fall');
            }
        }

        return $this->respondWithSuccess('Add user success');
    }
}
