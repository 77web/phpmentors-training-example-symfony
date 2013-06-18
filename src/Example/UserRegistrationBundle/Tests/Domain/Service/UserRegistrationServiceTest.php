<?php

namespace Example\UserRegistrationBundle\Tests\Domain\Service;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Example\UserRegistrationBundle\Domain\Data\User;
use Example\UserRegistrationBundle\Domain\Service\UserRegistrationService;

class UserRegistrationServiceTest extends TestCase
{
    public function testRegisterUser()
    {
        $em = \Phake::mock('Doctrine\ORM\EntityManager');

        $userRepository = \Phake::mock('Example\UserRegistrationBundle\Domain\Data\UserRepository');
        \Phake::when($em)->getRepository('Example\UserRegistrationBundle\Domain\Data\User')->thenReturn($userRepository);

        $registrationService = new UserRegistrationService($em);

        $user = new User();
        $registrationService->register($user);
        \Phake::verify($userRepository)->add($user);
        \Phake::verify($em)->flush();
    }
}