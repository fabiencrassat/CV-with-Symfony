<?php

namespace Nbs\Bundle\CVBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/Fabien');

        $this->assertTrue($crawler->filter('html:contains("Fabien Crassat")')->count() > 0);
    }
}
