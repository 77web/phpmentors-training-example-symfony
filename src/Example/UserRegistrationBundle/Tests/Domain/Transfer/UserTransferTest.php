<?php


namespace Example\UserRegistrationBundle\Tests\Domain\Transfer;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Example\UserRegistrationBundle\Domain\Transfer\UserTransfer;

class UserTransferTest extends TestCase
{
    public function testSend()
    {
        $mailer = \Phake::mock('Swift_Mailer');
        $templating = \Phake::mock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');

        $transfer = new UserTransfer('info@77-web.com', $mailer, $templating);

        $user = \Phake::mock('Example\UserRegistrationBundle\Domain\Data\User');
        $transfer->sendActivationMail($user);

        \Phake::verify($templating)->render($this->isType(\PHPUnit_Framework_Constraint_IsType::TYPE_STRING), $this->isType(\PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY));
        \Phake::verify($mailer)->send($this->isInstanceOf('Swift_Message'));
    }
}