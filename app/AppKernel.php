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
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            //new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            new JMS\JobQueueBundle\JMSJobQueueBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle($this),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),
            new BeSimple\SoapBundle\BeSimpleSoapBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Escape\WSSEAuthenticationBundle\EscapeWSSEAuthenticationBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),
            new Bazinga\ExposeTranslationBundle\BazingaExposeTranslationBundle(),
            new APY\JsFormValidationBundle\APYJsFormValidationBundle(),
            new Genemu\Bundle\FormBundle\GenemuFormBundle(),
            new A2lix\TranslationFormBundle\A2lixTranslationFormBundle(),
            new JDare\ClankBundle\JDareClankBundle(),
            new Lexik\Bundle\MaintenanceBundle\LexikMaintenanceBundle(),
            new Sylius\Bundle\FlowBundle\SyliusFlowBundle(),

            // BAPP bundles
            new OroPro\Bundle\EwsBundle\OroProfessionalEwsBundle(),

            // CRM bundles
            new OroCRM\Bundle\AccountBundle\OroCRMAccountBundle(),
            new OroCRM\Bundle\ContactBundle\OroCRMContactBundle(),
            new OroCRM\Bundle\DashboardBundle\OroCRMDashboardBundle(),
            new OroCRM\Bundle\SalesBundle\OroCRMSalesBundle(),
            new OroCRM\Bundle\ReportBundle\OroCRMReportBundle(),
            new OroCRM\Bundle\DemoDataBundle\OroCRMDemoDataBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        if (in_array($this->getEnvironment(), array('test'))) {
            $bundles[] = new Oro\Bundle\TestFrameworkBundle\OroTestFrameworkBundle();
        }

        // BAP bundles
        $bundles = array_merge($bundles, Oro\Bundle\PlatformBundle\OroPlatformBundle::registeredBundles($this));

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }

    protected function initializeContainer()
    {
        static $first = true;

        if ('test' !== $this->getEnvironment()) {
            parent::initializeContainer();
            return;
        }

        $debug = $this->debug;

        if (!$first) {
            // disable debug mode on all but the first initialization
            $this->debug = false;
        }

        // will not work with --process-isolation
        $first = false;

        try {
            parent::initializeContainer();
        } catch (\Exception $e) {
            $this->debug = $debug;
            throw $e;
        }

        $this->debug = $debug;
    }
}
