<?php

namespace Nbs\Bundle\CVBundle\Tests\Entity;

use Nbs\Bundle\CVBundle\Entity\CurriculumVitae;

class CurriculumVitaeTest extends \PHPUnit_Framework_TestCase
{
    public function testAge()
    {
    	$CV = new CurriculumVitae();
    	
    	$age = $CV->getAge("05/05/1981", "mm/dd/yy");
		// vérifie que votre classe a correctement calculé!
        $this->assertEquals(32, $age);

    	$age = $CV->getAge(date('n')."/".date('j')."/".date('Y'), "mm/dd/yy");
		// vérifie que votre classe a correctement calculé!
        $this->assertEquals(0, $age);

    	$age = $CV->getAge("12/31/2012", "mm/dd/yy");
		// vérifie que votre classe a correctement calculé!
        $this->assertEquals(0, $age);
    }
}
