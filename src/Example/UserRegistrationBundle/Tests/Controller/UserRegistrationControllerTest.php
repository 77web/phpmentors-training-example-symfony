<?php

namespace Example\UserRegistrationBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserRegistrationControllerTest extends WebTestCase
{
    public function setUp()
    {
        $client = static::createClient();

        $em = $client->getContainer()->get('doctrine')->getManager();
        $tool = new \Doctrine\ORM\Tools\SchemaTool($em);

        $classes = array(
            $em->getClassMetadata('Example\UserRegistrationBundle\Domain\Data\User'),
        );
        $tool->dropDatabase();
        $tool->createSchema($classes);
    }

    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/users/registration/');

        $this->assertEquals(1, $crawler->filter('form')->count());
        $this->assertEquals(1, $crawler->filter('#user_firstName')->count());
        $this->assertEquals(1, $crawler->filter('#user_lastName')->count());
        $this->assertEquals(1, $crawler->filter('#user_email')->count());
        $this->assertEquals(1, $crawler->filter('#user_password_password')->count());
        $this->assertEquals(1, $crawler->filter('#user_password_confirmation_password')->count());
    }

    public function testUserRegistrationFlow()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/users/registration/');
        $form = $crawler->selectButton('次へ')->form();
        $client->submit($form, array('user' => array(
                'firstName' => 'Hiromi',
                'lastName' => 'Hishida',
                'email' => 'info@77-web.com',
                'password' => array(
                    'password' => 'password',
                    'confirmation_password' => 'password',
                ),
            )));
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $client->getResponse());

        //確認画面
        $crawler = $client->followRedirect();
        $this->assertEquals(1, $crawler->filter('table td:contains("Hishida Hiromi")')->count());
        $this->assertEquals(1, $crawler->filter('table td:contains("info@77-web.com")')->count());
        $form = $crawler->selectButton('登録する')->form();
        $client->submit($form);
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $client->getResponse());

        $crawler = $client->followRedirect();
        $this->assertEquals(1, $crawler->filter('p:contains("登録が完了しました。")')->count());
    }

    public function tearDown()
    {
        $client = static::createClient();

        $em = $client->getContainer()->get('doctrine')->getManager();
        $tool = new \Doctrine\ORM\Tools\SchemaTool($em);
        $tool->dropDatabase();
    }
}
