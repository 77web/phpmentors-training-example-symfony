<?php

namespace Example\UserRegistrationBundle\Domain\Service;

use Doctrine\ORM\EntityManager;
use Example\UserRegistrationBundle\Domain\Data\User;

class UserRegistrationService
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function register(User $user)
    {
        //アクティベーションって何するの？

        $this->em->persist($user);
        $this->em->flush();
    }
}