<?php

namespace Example\UserRegistrationBundle\Domain\Service;

use Doctrine\ORM\EntityManager;
use Example\UserRegistrationBundle\Domain\Data\User;

class UserRegistrationService
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function register(User $user)
    {
        //アクティベーションって何するの？

        $this->entityManager->getRepository('Example\UserRegistrationBundle\Domain\Data\User')->add($user);
        $this->entityManager->flush();
    }
}