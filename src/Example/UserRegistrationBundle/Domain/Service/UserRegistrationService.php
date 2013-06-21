<?php

namespace Example\UserRegistrationBundle\Domain\Service;

use Doctrine\ORM\EntityManager;
use Example\UserRegistrationBundle\Domain\Data\User;
use Example\UserRegistrationBundle\Domain\Transfer\UserTransfer;
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
     * @var UserTransfer
     */
    private $userTransfer;

    /**
     * @param EntityManager $entityManager
     * @param PasswordEncoderInterface $passwordEncoder
     * @param SecureRandomInterface $secureRandom
     */
    public function __construct(EntityManager $entityManager, PasswordEncoderInterface $passwordEncoder, SecureRandomInterface $secureRandom, UserTransfer $userTransfer)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->secureRandom = $secureRandom;
        $this->userTransfer = $userTransfer;
    }

    /**
     * @param User $user
     */
    public function register(User $user)
    {
        $user->setActivationKey(base64_encode($this->secureRandom->nextBytes(24)));
        $user->setPassword($this->passwordEncoder->encodePassword($user->getPassword(), User::SALT));
        $user->setRegistrationDate(new \DateTime());

        $this->entityManager->getRepository('Example\UserRegistrationBundle\Domain\Data\User')->add($user);
        $this->entityManager->flush();

        if (!$this->userTransfer->sendActivationMail($user)) {
            throw new \Exception('Could not send activation mail.');
        }
    }

    /**
     * @param string $activationKey
     * @throws \Exception
     */
    public function activate($activationKey)
    {
        $user = $this->entityManager->getRepository('Example\UserRegistrationBundle\Domain\Data\User')->findOneByActivationKey($activationKey);
        if (!$user) {
            throw new \Exception('Could not find proper user.');
        }

        if ($user->getActivationDate()) {
            throw new \Exception('User has been already activated.');
        }

        $user->setActivationDate(new \DateTime());
        $user->setActivationKey('');

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}