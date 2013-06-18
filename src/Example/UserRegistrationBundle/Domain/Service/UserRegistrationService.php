<?php

namespace Example\UserRegistrationBundle\Domain\Service;

use Doctrine\ORM\EntityManager;
use Example\UserRegistrationBundle\Domain\Data\User;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Util\SecureRandomInterface;

class UserRegistrationService
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var PasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var \Symfony\Component\Security\Core\Util\SecureRandomInterface
     */
    private $secureRandom;

    /**
     * @param EntityManager $entityManager
     * @param PasswordEncoderInterface $passwordEncoder
     * @param SecureRandomInterface $secureRandom
     */
    public function __construct(EntityManager $entityManager, PasswordEncoderInterface $passwordEncoder, SecureRandomInterface $secureRandom)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->secureRandom = $secureRandom;
    }

    public function register(User $user)
    {
        $user->setActivationKey(base64_encode($this->secureRandom->nextBytes(24)));
        $user->setPassword($this->passwordEncoder->encodePassword($user->getPassword(), User::SALT));

        $this->entityManager->getRepository('Example\UserRegistrationBundle\Domain\Data\User')->add($user);
        $this->entityManager->flush();
    }
}