<?php


namespace Example\UserRegistrationBundle\Tests\Domain\Transfer;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Example\UserRegistrationBundle\Domain\Transfer\UserTransfer;

class UserTransferTest extends TestCase
{
    public function testSend()
    {
        $mailer = \Phake::mock('Swift_Mailer');
        $messageFactory = \Phake::mock('Swift_Message');
        $templateLoader = \Phake::mock('Twig_Environment');
        $template = \Phake::mock('Twig_TemplateInterface');
        \Phake::when($templateLoader)->loadTemplate($this->anything())->thenReturn($template);
        $transfer = new UserTransfer($mailer, $messageFactory, $templateLoader);

        $user = \Phake::mock('Example\UserRegistrationBundle\Domain\Data\User');
        $transfer->sendActivationMail($user);

        \Phake::verify($template)->render($this->isType(\PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY));
        \Phake::verify($mailer)->send($this->isInstanceOf('Swift_Message'));
    }
}