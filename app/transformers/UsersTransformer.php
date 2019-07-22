<?php

class UsersTransformer extends BaseTransformer
{
    public function  transform(Users $user)
    {
        return [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'phone' => $user->getPhone(),
            'fullName' => $user->getFullName()
        ];
    }
}