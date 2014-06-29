<?php

namespace Nbs\Bundle\CVBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TestMailController extends Controller
{
    public function indexAction()
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('fabien.crassat@gmail.com')
            ->setTo('fabien@crassat.com')
            ->setBody("coucou")
        ;
        $this->get('mailer')->send($message);

         return new Response("<html><body>mail sent</body></html>");
    }
}