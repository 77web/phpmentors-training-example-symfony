<?php

namespace Example\UserRegistrationBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Example\UserRegistrationBundle\Domain\Data\User;

class UserActivationControllerTest extends WebTestCase
{
    public function setUp()
    {
        $client = static::createClient();

        $em = $client->getContainer()->get('doctrine')->getManager();
        $user = new User();
        $user
            ->setFirstName('test')
            ->setLastName('test')
            ->setPassword('test')
            ->setEmail('info@77-web.com')
            ->setActivationKey('test')
            ->setRegistrationDate(new \DateTime())
        ;
        $em->persist($user);
        $em->flush();

    }

    public function testActivation()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/users/activation/?key=test');

        $this->assertEquals(1, $crawler->filter('p:contains("アカウント有効化が完了しました。")')->count());
    }
}