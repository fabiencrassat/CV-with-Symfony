<?php
// src/Nbs/Bundle/CVBundle/Controller/TestController.php

namespace Nbs\Bundle\CVBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;

use Nbs\Bundle\CVBundle\Entity\CurriculumVitae;

class DefaultController extends Controller
{
    private $Lang;
    private $ReadCVXml;
    private $FileToLoad;

    public function indexAction($cvxmlfile, $_locale)
    {
        $this->FileToLoad = (string) $cvxmlfile;
        $this->Lang = (string) $_locale;

        $this->ReadCVXml = new CurriculumVitae($this->FileToLoad, $this->Lang);

        return $this->render('NbsCVBundle:Default:index.html.twig', array(
            'cvxmlfile'         => $this->FileToLoad,
            'language'          => $this->getDropDownLanguages(),
            'anchors'           => $this->ReadCVXml->getAnchors(),
            'identity'          => $this->ReadCVXml->getIdentity(),
            'followMe'          => $this->ReadCVXml->getFollowMe(),
            'lookingFor'        => $this->ReadCVXml->getLookingFor(),
            'experiences'       => $this->ReadCVXml->getExperiences(),
            'skills'            => $this->ReadCVXml->getSkills(),
            'educations'        => $this->ReadCVXml->getEducations(),
            'languageSkills'    => $this->ReadCVXml->getLanguageSkills(),
            'miscellaneous'     => $this->ReadCVXml->getMiscellaneous(),
            'society'           => $this->ReadCVXml->getSociety(),
        ));
    }

    private function getDropDownLanguages()
    {
        $asLanguages = array(
            "en" => "English",
            "fr" => "Français",
        );

        // On récupère le service translator
        $translator = $this->get('translator');

        $dropdownItems = array();
        $i = 0;
        foreach ($asLanguages as $key => $value) {
            $dropdownItems[$i++] = array(
                'attribute' => (string) $key,
                'value' => (string) $value
            );
        };

        $language = array(
            'dropdownTitle' => $translator->trans('Language'),
            'items'         => $dropdownItems
        );

        return $language;
    }
}