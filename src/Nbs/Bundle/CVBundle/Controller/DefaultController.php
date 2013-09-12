<?php

namespace Nbs\Bundle\CVBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;

use Nbs\Bundle\CVBundle\Entity\CurriculumVitae;

class DefaultController extends Controller
{
    public function indexAction($cvxmlfile, $_locale)
    {
        $FileToLoad = (string) $cvxmlfile;
        $Lang = (string) $_locale;

        $XML = new CurriculumVitae($FileToLoad, $Lang);

        return $this->render('NbsCVBundle:Default:index.html.twig', array(
            'cvxmlfile'         => $cvxmlfile,
            'language'          => $XML->getDropDownLanguages(),
            'anchors'           => $XML->getAnchors(),
            'identity'          => $XML->getIdentity(),
            'lookingFor'        => $XML->getLookingFor(),
            'followMe'          => $XML->getFollowMe(),
            'experiences'       => $XML->getExperiences(),
            'educations'        => $XML->getEducations(),
            'skills'            => $XML->getSkills(),
            'languageSkills'    => $XML->getLanguageSkills(),
            'miscellaneous'     => $XML->getMiscellaneous(),
            'society'           => $XML->getSociety(),
        ));
    }
}