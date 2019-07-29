<?php

class FollowController extends ControllerBase
{
    public function add()
    {
        $data = $this->parserDataRequest();

        if (!$this->getCurrentUser()) {
            return $this->respondWithError('Unauthorized');
        }
        $userId = $this->getCurrentUser()->id;
        $data['followerId'] = $userId;
        $data['followingId'] = $data['userId'];
        if (Followers::isFollow($data)) {
            return $this->respondWithError('you have already following');
        }
        $follow = new Followers();
        $follow->setFollowerId($userId);
        $follow->setFollowingId($data['userId']);
        if (!$follow->save()) {
            return $this->respondWithError('Follower failed');
        }
        return $this->respondWithSuccess('Follower success');

    }

    public function delete()
    {
        $data = $this->parserDataRequest();
        if (!$this->getCurrentUser()) {
            return $this->respondWithError('Unauthorized');
        }
        $userId = $this->getCurrentUser()->id;
        $data['followerId'] = $userId;
        $data['followingId'] = $data['userId'];
        $follow = Followers::isFollow($data);
        if (!$follow) {
            return $this->respondWithError('you have not following');
        }
        $follow->setStatus('delete');
        if (!$follow->save()) {
            return $this->respondWithError('Follower update failed');
        }
        return $this->respondWithSuccess('Un follower success');
    }

    public function me()
    {
        return $this->respondWithArray(Followers::getNumberFollow($this->getCurrentUser()->id));
    }
}
