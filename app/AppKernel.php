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
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Genemu\Bundle\FormBundle\GenemuFormBundle(),
            new CoreSphere\ConsoleBundle\CoreSphereConsoleBundle(),
            new Oryzone\Bundle\BoilerplateBundle\OryzoneBoilerplateBundle(),
            new Mremi\ContactBundle\MremiContactBundle(),
            new Mremi\BootstrapBundle\MremiBootstrapBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            // $bundles[] = new CoreSphere\ConsoleBundle\CoreSphereConsoleBundle();
        }

        // The last translation file always wins.
        // That mean that you need to make sure that the bundle containing your translations is loaded
        // after any bundle whose translations you're overriding.
        $bundles[] = new Nbs\Bundle\CVBundle\NbsCVBundle();

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
