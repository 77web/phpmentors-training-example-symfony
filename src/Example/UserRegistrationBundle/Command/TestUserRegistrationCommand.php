<?php


namespace Example\UserRegistrationBundle\Command;

use Example\UserRegistrationBundle\Domain\Factory\UserFactory;
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

        $userRegistrationService = $this->getContainer()->get('example.user_registration');
        $userRegistrationService->register($user);

        $output->writeln('Registration complete for user:'.$user->getId());
    }
}