<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Oryzone\Bundle\BoilerplateBundle\OryzoneBoilerplateBundle(),
            new Mremi\ContactBundle\MremiContactBundle(),
            new Mremi\BootstrapBundle\MremiBootstrapBundle(),
            new Genemu\Bundle\FormBundle\GenemuFormBundle(),
            new Knp\Bundle\SnappyBundle\KnpSnappyBundle(),
            new FabienCrassat\CurriculumVitaeBundle\FabienCrassatCurriculumVitaeBundle(),
            new Nbs\Bundle\CVBundle\NbsCVBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Lsw\AutomaticUpdateBundle\LswAutomaticUpdateBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
