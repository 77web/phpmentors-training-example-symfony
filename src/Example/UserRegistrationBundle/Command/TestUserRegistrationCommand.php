<?php


namespace Example\UserRegistrationBundle\Command;

use Example\UserRegistrationBundle\Domain\Factory\UserFactory;
use Example\UserRegistrationBundle\Domain\Service\UserRegistrationService;
use Example\UserRegistrationBundle\Domain\Transfer\UserTransfer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestUserRegistrationCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:test:user:registration')
            ->setDescription('ユーザー登録のテスト用コマンド')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userFactory = new UserFactory();
        $user = $userFactory->create();

        $user
            ->setFirstName('Hiromi')
            ->setLastName('Hishida')
            ->setEmail('info@77-web.com')
            ->setPassword('password')
        ;

        $userRegistrationService = new UserRegistrationService(
            $this->getContainer()->get('doctrine.orm.entity_manager'),
            $this->getContainer()->get('security.encoder_factory')->getEncoder(get_class($user)),
            $this->getContainer()->get('security.secure_random'),
            new UserTransfer($this->getContainer()->get('mailer'), new \Swift_Message(), $this->getContainer()->get('twig'))
        );
        $userRegistrationService->register($user);

        $output->writeln('Registration complete for user:'.$user->getId());

        $this->getContainer()->get('doctrine')->getEntityManager()->detach($user);
        $userRegistrationService->activate($user->getActivationKey());
        $output->writeln('Activation complete for user:'.$user->getId());
    }
}