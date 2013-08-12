<?php

namespace Nbs\Bundle\CVBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

	    /**
	     * @Route("/fabien/{_locale}", name="nbs_cv_local")
	     * @Template()
	     */
        $crawler = $client->request('GET', '/fabien');
		$this->assertGreaterThan(0, $crawler->filter('html:contains("Fabien Crassat")')->count());

        $crawler = $client->request('GET', '/fabien/fr');
		$this->assertGreaterThan(0, $crawler->filter('html:contains("Fabien Crassat")')->count());
    }
}
