<?php
namespace App\Controllers;

use App\Aws\Storage;
use App\Aws\UploadType;
use App\Image\ImageManager;
use App\Models\Services\User;
use App\Models\Users;
use App\Transformers\UsersTransformer;

/**
 * Class UsersController
 * @property Storage storage
 */
class UsersController extends ControllerBase
{

    /**
     * @return \Phalcon\Http\ResponseInterface
     */
    public function index()
    {
        $user = new Users();
        dd($user->toArray());
    }

    /**
     * @param $email
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function item($email)
    {
    }
    /**
     * @return \Phalcon\Http\ResponseInterface
     */
    public function add()
    {
        
        $data = $this->parserDataRequest();
        if (!isset($data['email']) || !isset($data['password']) ||
            !isset($data['username'])) {
            return $this->respondWithError('You need provider email and password');
        }
        $userService = new User();
        if ($userService->findFirstByEmailOrUsername($data['email'])) {
            return $this->respondWithError('That email is taken. Try another');
        }
        if ($userService->findFirstByEmailOrUsername($data['username'])) {
            return $this->respondWithError('That username is taken. Try another');
        }
        $user = new Users();
        $user->setRoleId(Users::ROLE_BUYER);
        $data['password'] = $this->security->hash($data['password']);
        if (!$user->save($data)) {
            foreach ($user->getMessages() as $m) {
                return $this->respondWithError('Add user fall '. $m->getMessage());
            }
        }
        return $this->respondWithItem($user, new UsersTransformer([]));
    }
    public function update()
    {
    }

    /**
     * @return \Phalcon\Http\ResponseInterface
     */
    public function avatar()
    {
        $file = $_FILES['file'] ?? [];
        if (!isset($file)) {
            return $this->respondWithError('Nothing a data file', 404);
        }
        //File avatar need small then 4M
        if (!isset($file['size']) || $file['size'] > UploadType::AVATAR_MAX_SIZE) {
            return $this->respondWithError('Sorry, your file is too large', 404);
        }
        if ($this->request->hasFiles()) {
            $files = $this->request->getUploadedFiles();
            foreach ($files as $file) {
                $fileName = md5($this->getCurrentUser()->id) . '.' . $file->getExtension();
                $fileName = UploadType::USER_AVATAR . '/' . $fileName;
                $this->resizeAvatar($fileName, $file);
                // Move the file into the application
                // For best practice we should put this action in queue
                if (!$this->storage->uploadImage($fileName, $file)) {
                    return $this->respondWithError('Update avatar not success');
                }
                $user = Users::findFirst($this->getCurrentUser()->id);
                if (!$user) {
                    //@TODO
                    return $this->respondWithError('Unauthorized');
                }
                $user->setImage($fileName);
                $user->save();
                return $this->respondWithSuccess('Update avatar success');
            }
        }
        return $this->respondWithError('Update avatar not success');
    }

    public function password()
    {
    }
    public function me()
    {
        if ($this->getCurrentUser()) {
            return $this->respondWithArray(get_object_vars($this->getCurrentUser()));
        }
        return $this->respondWithError('Unauthorized');
    }

    /**
     * @param $fileName
     * @param object $file
     */
    protected function resizeAvatar($fileName, $file)
    {
        $resize = new ImageManager();
        $item['fileName'] = $fileName;
        $item['path'] = $file->getTempName();
        $item['width'] = $item['height'] = 300;
        return $resize->resize($item);
    }
}
