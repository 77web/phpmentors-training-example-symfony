<?php

namespace Example\UserRegistrationBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserActivationControllerTest extends WebTestCase
{
    public function testDo()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/users/activation/');

        $this->assertEquals(1, $crawler->filter('p:contains("アカウント有効化が完了しました。")')->count());
    }
}