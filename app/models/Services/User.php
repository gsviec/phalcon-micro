<?php

namespace App\Models\Services;

use App\Models\Services\Exceptions\EntityException;
use App\Models\Services\Exceptions\EntityNotFoundException;
use App\Models\Users;

/**
 * \App\Models\Services\User
 *
 * @package App\Models\Services
 */
class User extends Service
{
    /**
     * @var Users
     */
    protected $viewer;

    /**
     * Finds User by ID.
     *
     * @param  int $id The User ID.
     * @return Users|null
     */
    public function findFirstById($id)
    {
        return Users::findFirstById($id) ?: null;
    }

    /**
     * Get User by ID.
     *
     * @param  int $id The User ID.
     * @return Users
     *
     * @throws Exceptions\EntityNotFoundException
     */
    public function getFirstById($id)
    {
        if (!$user = $this->findFirstById($id)) {
            throw new EntityNotFoundException($id, 'userId');
        }
        return $user;
    }

    /**
     * Finds User by email address.
     *
     * @param  string $email The email address.
     * @return Users|null
     */
    public function findFirstByEmail($email)
    {
        $user = Users::query()
            ->where('email = :email:', ['email' => $email])
            ->limit(1)
            ->execute();

        return $user->valid() ? $user->getFirst() : null;
    }

    /**
     * Get User by email address.
     *
     * @param  string $email The email address.
     * @return Users
     *
     * @throws Exceptions\EntityNotFoundException
     */
    public function getFirstByEmail($email)
    {
        if (!$user = $this->findFirstByEmail($email)) {
            throw new EntityNotFoundException($email, 'email');
        }

        return $user;
    }

    /**
     * Finds User by username.
     *
     * @param  string $name The username.
     * @return Users|null
     */
    public function findFirstByUsername($name)
    {
        $user = Users::query()
            ->where('username = :name:', ['name' => $name])
            ->limit(1)
            ->execute();

        return $user->valid() ? $user->getFirst() : null;
    }

    /**
     * Get User by username.
     *
     * @param  string $name The username.
     * @return Users
     *
     * @throws Exceptions\EntityNotFoundException
     */
    public function getFirstByUsername($name)
    {
        if (!$user = $this->findFirstByUsername($name)) {
            throw new Exceptions\EntityNotFoundException($name, 'username');
        }

        return $user;
    }

    /**
     * Finds User by email or username.
     *
     * @param  string $name The username.
     * @return Users|null
     */
    public function findFirstByEmailOrUsername($name)
    {
        $user = Users::query()
            ->where('email = :email:', ['email' => $name])
            ->orWhere('username = :name:', ['name' => $name])
            ->limit(1)
            ->execute();

        return $user->valid() ? $user->getFirst() : null;
    }

    /**
     * Get User by email or username.
     *
     * @param  string $name The username.
     * @return Users
     *
     * @throws EntityNotFoundException
     */
    public function getFirstByEmailOrUsername($name)
    {
        if (!$user = $this->findFirstByEmailOrUsername($name)) {
            throw new EntityNotFoundException($name, 'email or username');
        }

        return $user;
    }

    /**
     * Finds User by registerHash.
     *
     * @param  string $hash The hash string generated on sign up time.
     * @return Users|null
     */
    public function findFirstByRegisterHash($hash)
    {
        $user = Users::query()
            ->where('registerHash = :hash:', ['hash' => $hash])
            ->limit(1)
            ->execute();

        return $user->valid() ? $user->getFirst() : null;
    }

    /**
     * Get User by registerHash.
     *
     * @param  string $hash The hash string generated on sign up time.
     * @return Users
     *
     * @throws Exceptions\EntityNotFoundException
     */
    public function getFirstByRegisterHash($hash)
    {
        if (!$user = $this->findFirstByRegisterHash($hash)) {
            throw new Exceptions\EntityNotFoundException($hash, 'registerHash');
        }

        return $user;
    }

    /**
     * Finds User by passwordForgotHash.
     *
     * @param  string $hash The hash string generated on sign up time.
     * @return Users|null
     */
    public function findFirstByPasswordForgotHash($hash)
    {
    }

    /**
     * Get User by passwordForgotHash.
     *
     * @param  string $hash The hash string generated on reset password up time.
     * @return Users
     *
     * @throws Exceptions\EntityNotFoundException
     */
    public function getFirstByPasswordForgotHash($hash)
    {
        if (!$user = $this->findFirstByRegisterHash($hash)) {
            throw new Exceptions\EntityNotFoundException($hash, 'passwordForgotHash');
        }

        return $user;
    }

    /**
     * Checks whether the User is active.
     *
     * @param  Users $user
     * @return bool
     */
    public function isActiveMember(Users $user)
    {
    }



    /**
     * Register a new member and returns register unique URL to confirm registration.
     *
     * @param Users $entity
     *
     * @return string
     * @throws Exceptions\EntityException
     */
    public function registerNewMemberOrFail(Users $entity)
    {
    }

    /**
     * Confirm registration the new membership.
     *
     * @param Users  $entity
     * @param string $password
     *
     * @throws Exceptions\EntityException
     */
    public function confirmRegistration(Users $entity, $password)
    {
    }



    /**
     * Reset password for user.
     *
     * @param  Users $entity
     * @return array
     *
     * @throws Exceptions\EntityException
     */
    public function resetPassword(Users $entity)
    {
    }

    /**
     * Assign a new password for the User.
     *
     * @param  Users  $entity
     * @param  string $password
     *
     * @throws Exceptions\EntityException
     */
    public function assignNewPassword(Users $entity, $password)
    {
    }



    /**
     * Sets current viewer.
     *
     * @param Users $entity
     */
    public function setCurrentViewer(Users $entity)
    {
        $this->viewer = $entity;
    }


    /**
     * Checks whether the User is Admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
    }
    /**
     * Checks whether the User is moderator.
     *
     * @return bool
     */
    public function isModerator()
    {
    }
}
