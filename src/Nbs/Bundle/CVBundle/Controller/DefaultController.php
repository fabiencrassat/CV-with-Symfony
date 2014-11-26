<?php

namespace Nbs\Bundle\CVBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('NbsCVBundle:Default:index.html.twig', array('name' => $name));
    }
}
