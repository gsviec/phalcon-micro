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
        $user = new Users();
        $data['password'] = $this->security->hash($data['password']);

        if (!$user->save($data)) {
            foreach ($user->getMessages() as $m) {
                return $this->respondWithError('Add user fall');
            }
        }
        return $this->respondWithItem($user, new UsersTransformer([]));
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
    public function avatar()
    {
        $file = $_FILES['file'] ?? [];
        if (!isset($file)) {
            return $this->respondWithError('Nothing a data file', 404);
        }
        //File need small then 50M
        if (!isset($file['size']) || $file['size'] > 52428800) {
            return $this->respondWithError('Sorry, your file is too large', 404);
        }

        if ($this->request->hasFiles()) {
            $files = $this->request->getUploadedFiles();
            // Print the real file names and sizes
            foreach ($files as $file) {
                $fileName = md5($this->getCurrentUser()->id);
                // Move the file into the application
                $file->moveTo(
                    'files/' . $fileName . '.png'
                );
                $user = Users::findFirst($this->getCurrentUser()->id);
                if (!$user) {
                    //@TODO
                    return $this->respondWithError('Unauthorized');
                }
                $user->setAvatar($fileName);
                $user->save();
                return $this->respondWithSuccess('Update avatar success');

            }
        }
        return $this->respondWithError('Update avatar not success');
    }
}
