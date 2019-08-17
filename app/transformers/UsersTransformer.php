<?php

namespace App\Transformers;

use App\Models\Users;

/**
 * Class UsersTransformer
 * @package App\Transformers
 */
class UsersTransformer extends BaseTransformer
{
    /**
     * @param Users $user
     *
     * @return array
     */
    public function transform(Users $user)
    {
        return [
            'id' => $user->getId(),
            'roleId' => $user->getRoleId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'title' => $user->getTitle(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'image' => $user->getImage(),
            'cover' => $user->getCover(),
            'country' => $user->getCountry(),
            'zone' => $user->getZone(),
            'address' => $user->getAddress(),
            'zipcode' => $user->getZipcode(),
            'profile' => $user->getProfile(),
            'bio' => $user->getBio(),
            'url' => $user->getUrl(),
            'amount' => $user->getAmount(),
            'deposited' => $user->getDeposited(),
            'lastLogin' => $user->getLastLogin(),
            'active' => $user->getActiveDate(),
            'language' => $user->getLanguage(),
            'specialRoles' => $user->getSpecialRoles(),
            'creationDate' => $user->getCreationDate(),
            'modifiedDate' => $user->getModifiedDate(),
            'activeDate' => $user->getActiveToken(),
            'artistNameUrl' => $user->getArtistNameUrl(),
            'organizational' => $user->getOrganizational(),
            'emailNotification' => $user->getEmailNotification(),
            'deviceRegistered' => $user->getDeviceRegistered(),
        ];
    }
}
