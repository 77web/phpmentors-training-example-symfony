<?php

namespace Example\UserRegistrationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ExampleUserRegistrationBundle extends Bundle
{
    public function boot()
    {
        $this->configureMail();
    }

    protected function configureMail()
    {
        \Swift::init(function(){
                \Swift_DependencyContainer::getInstance()
                    ->register('mime.qpheaderencoder')
                    ->asAliasOf('mime.base64headerencoder')
                ;
                \Swift_Preference::getInstance()->setCharset('iso-2022-jp');
            });
    }
}
