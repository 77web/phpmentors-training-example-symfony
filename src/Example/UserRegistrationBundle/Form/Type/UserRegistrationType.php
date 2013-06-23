<?php

namespace Example\UserRegistrationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName')
            ->add('firstName')
            ->add('email')
            ->add('password', 'repeated', array(
                    'type' => 'password',
                    'first_name' => 'password',
                    'second_name' => 'confirmation_password',
                    'invalid_message' => 'パスワードが確認用パスワードと一致しません。',
                    'first_options' => array(
                        'label' => 'パスワード',
                    ),
                    'second_options' => array(
                        'label' => '確認用パスワード',
                    ),
                ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Example\UserRegistrationBundle\Domain\Data\User',
            'validation_groups' => array('registration'),
        ));
    }

    public function getName()
    {
        return 'user';
    }
}
