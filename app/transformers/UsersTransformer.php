<?php

class UsersTransformer extends BaseTransformer
{
    public function  transform(Users $user)
    {
        return [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'phone' => $user->getPhone(),
            'bio' => $user->getBio(),
            'birthDate' => $user->getBirthDate(),
            'avatarUrl' => $user->getAvatarUrl(),
            'fullName' => $user->getFullName(),
            'createdAt' => $user->getCreatedAt()
        ];
    }
}