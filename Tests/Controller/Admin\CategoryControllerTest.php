<?php

namespace Vertacoo\LinksDirectoryBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class Admin\CategoryControllerTest extends WebTestCase
{
    public function testList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/list');
    }

    public function testShow()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/show');
    }

}
