<?php

namespace Example\UserRegistrationBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserRegistrationControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(1, $crawler->filter('form')->count());
        $this->assertEquals(1, $crawler->filter('#user_firstName')->count());
        $this->assertEquals(1, $crawler->filter('#user_lastName')->count());
        $this->assertEquals(1, $crawler->filter('#user_email')->count());
        $this->assertEquals(1, $crawler->filter('#user_password')->count());
    }

    public function testDo()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
        $form = $crawler->selectButton('登録')->form();
        $client->submit($form, array('user' => array(
                'firstName' => 'Hiromi',
                'lastName' => 'Hishida',
                'email' => 'info@77-web.com',
                'password' => 'password',
            )));
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $client->getResponse());

        $crawler = $client->followRedirect();
        $this->assertEquals(1, $crawler->filter('p:contains("登録が完了しました。")')->count());
    }

}
