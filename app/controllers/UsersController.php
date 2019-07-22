<?php

class UsersController extends ControllerBase
{
    public function index()
    {
        var_dump('expression');
    }

    public function show($name)
    {
        // ...
    }

    public function add()
    {
    	$data = $this->parserDataRequest();
   	if (!isset($data['email'])) {
   		return  $this->respondError('You need provider email');
   	}
   	$user = Users::findFirstByEmail($data['email']);
   	if (!$user) {
   		$user = new Users();
   		$data['password'] =  $this->security->hash($data['password']);
   	}

   	if (!$user->save($data)) {
   		foreach ($user->getMessages() as $m) {
                	return  $this->respondWithError('Add user fall');
        	}
   	}

   	return  $this->respondWithSuccess('Add user success');
    }
}
