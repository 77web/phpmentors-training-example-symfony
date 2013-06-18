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
        $activationKey = 'activationkey';

        $userRepository = \Phake::mock('Example\UserRegistrationBundle\Domain\Data\UserRepository');
        \Phake::when($em)->getRepository('Example\UserRegistrationBundle\Domain\Data\User')->thenReturn($userRepository);

        $encoder = \Phake::mock('Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface');
        \Phake::when($encoder)->encodePassword($this->anything(), $this->anything())->thenReturn($password);

        $secureRandom = \Phake::mock('Symfony\Component\Security\Core\Util\SecureRandomInterface');
        \Phake::when($secureRandom)->nextBytes($this->anything())->thenReturn($activationKey);

        $user = \Phake::mock('Example\UserRegistrationBundle\Domain\Data\User');
        \Phake::when($user)->getPassword()->thenReturn($password);

        $registrationService = new UserRegistrationService($em, $encoder, $secureRandom);
        $registrationService->register($user);

        \Phake::verify($secureRandom)->nextBytes($this->isType(\PHPUnit_Framework_Constraint_IsType::TYPE_INT));
        \Phake::verify($user)->setActivationKey($this->isType(\PHPUnit_Framework_Constraint_IsType::TYPE_STRING));
        \Phake::verify($user)->getPassword();
        \Phake::verify($encoder)->encodePassword($this->isType(\PHPUnit_Framework_Constraint_IsType::TYPE_STRING), $this->isType(\PHPUnit_Framework_Constraint_IsType::TYPE_STRING));
        \Phake::verify($user)->setPassword($this->isType(\PHPUnit_Framework_Constraint_IsType::TYPE_STRING));
        \Phake::verify($user)->setRegistrationDate($this->isInstanceOf('DateTime'));
        \Phake::verify($userRepository)->add($this->identicalTo($user));
        \Phake::verify($em)->flush();
    }
}