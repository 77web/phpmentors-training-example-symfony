<?php

namespace Example\UserRegistrationBundle\Tests\Domain\Service;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Example\UserRegistrationBundle\Domain\Service\UserRegistrationService;

class UserRegistrationServiceTest extends TestCase
{
    public function testRegisterUser()
    {
        $em = \Phake::mock('Doctrine\ORM\EntityManager');
        $password = 'test';

        $userRepository = \Phake::mock('Example\UserRegistrationBundle\Domain\Data\UserRepository');
        \Phake::when($em)->getRepository('Example\UserRegistrationBundle\Domain\Data\User')->thenReturn($userRepository);

        $encoder = \Phake::mock('Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface');
        \Phake::when($encoder)->encodePassword($this->anything(), $this->anything())->thenReturn($password);

        $user = \Phake::mock('Example\UserRegistrationBundle\Domain\Data\User');
        \Phake::when($user)->getPassword()->thenReturn($password);

        $registrationService = new UserRegistrationService($em, $encoder);
        $registrationService->register($user);

        \Phake::verify($user)->getPassword();
        \Phake::verify($encoder)->encodePassword($this->isType(\PHPUnit_Framework_Constraint_IsType::TYPE_STRING), $this->isType(\PHPUnit_Framework_Constraint_IsType::TYPE_STRING));
        \Phake::verify($user)->setPassword($this->isType(\PHPUnit_Framework_Constraint_IsType::TYPE_STRING));
        \Phake::verify($userRepository)->add($user);
        \Phake::verify($em)->flush();
    }
}