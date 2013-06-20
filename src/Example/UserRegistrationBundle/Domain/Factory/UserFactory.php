<?php


namespace Example\UserRegistrationBundle\Domain\Factory;

use Example\UserRegistrationBundle\Domain\Data\User;

class UserFactory
{
    /**
     * @return User
     */
    public function create()
    {
        return new User();
    }
}