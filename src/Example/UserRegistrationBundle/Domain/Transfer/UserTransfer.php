<?php


namespace Example\UserRegistrationBundle\Domain\Transfer;

use Example\UserRegistrationBundle\Domain\Data\User;

class UserTransfer
{
    /**
     * @var string
     */
    private static $ACTIVATION_EMAIL_ACTIVATION_URI = 'https://www.example.org/users/registration/activation/';

    /**
     * @var array
     */
    private static $ACTIVATION_EMAIL_FROM = array('info@77-web.com' => 'Exampleサービス');

    /**
     * @var string
     */
    private static $ACTIVATION_EMAIL_SUBJECT = 'Exampleサービス: ユーザー登録のご確認および完了手続きのご案内';

    /**
     * @var string
     */
    private static $ACTIVATION_EMAIL_TEMPLATE = 'ExampleUserRegistrationBundle:UserRegistration:activation_email.txt.twig';

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Swift_Message
     */
    protected $messageFactory;

    /**
     * @var \Twig_Environment
     */
    protected $templateLoader;

    /**
     * @param \Swift_Mailer $mailer
     * @param \Swift_Message $messageFactory
     * @param \Twig_Environment $templateLoader
     */
    public function __construct(\Swift_Mailer $mailer, \Swift_Message $messageFactory, \Twig_Environment $templateLoader)
    {
        $this->mailer = $mailer;
        $this->messageFactory = $messageFactory;
        $this->templateLoader = $templateLoader;
    }

    /**
     * @param User $user
     * @return boolean
     */
    public function sendActivationMail(User $user)
    {
        $sentRecipientCount = $this->mailer->send($this->messageFactory->newInstance()
                ->setCharset('iso-2022-jp')
                ->setEncoder(new \Swift_Mime_ContentEncoder_PlainContentEncoder('7bit'))
                ->setFrom(self::$ACTIVATION_EMAIL_FROM)
                ->setTo($user->getEmail())
                ->setSubject(self::$ACTIVATION_EMAIL_SUBJECT)
                ->setBody($this->templateLoader->loadTemplate(self::$ACTIVATION_EMAIL_TEMPLATE)->render(array(
                            'user' => $user,
                            'activationURI' => self::$ACTIVATION_EMAIL_ACTIVATION_URI.'?key='.rawurlencode($user->getActivationKey()),
                        )))
        );

        return $sentRecipientCount == 1;
    }
}