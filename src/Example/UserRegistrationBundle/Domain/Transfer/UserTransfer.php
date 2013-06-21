<?php


namespace Example\UserRegistrationBundle\Domain\Transfer;

use Example\UserRegistrationBundle\Domain\Data\User;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class UserTransfer
{
    /**
     * @var string
     */
    private $from;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface
     */
    private $templating;

    /**
     * @param string $from
     * @param \Swift_Mailer $mailer
     * @param EngineInterface $templating
     */
    public function __construct($from, \Swift_Mailer $mailer, EngineInterface $templating)
    {
        $this->from = $from;
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public function sendActivationMail(User $user)
    {
        $subject = '会員登録確認メール';
        $body = $this->templating->render(
            'ExampleUserRegistrationBundle:UserRegistration:activation_mail.txt.twig',
            array(
                'user' => $user,
            )
        );

        $message = \Swift_Message::newInstance()
                        ->setSubject($subject)
                        ->setBody($body)
                        ->setTo($user->getEmail())
                        ->setFrom($this->from)
        ;

        $this->mailer->send($message);
    }
}