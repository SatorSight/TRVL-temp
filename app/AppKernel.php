<?php

error_reporting(0);
ini_set('display_errors', 0);

$log = file_get_contents("php://input");
//$log = $HTTP_RAW_POST_DATA;
//file_put_contents($_SERVER['DOCUMENT_ROOT']."/service/detail_log.txt", "\n", FILE_APPEND);
//file_put_contents($_SERVER['DOCUMENT_ROOT']."/service/detail_log.txt", urldecode($log), FILE_APPEND);

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Service\ServiceBundle\Entity\Repository\OutputHandler;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
//            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new Service\ServiceBundle\ServiceServiceBundle()
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'), true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}

function exception_handler($exception) {

//    $file = $_SERVER['DOCUMENT_ROOT'].'/service/log.txt';
//    $current = file_get_contents($file);
//    $current .= date('d.m.Y H.i.s')."::ERROR::: ".$exception->getMessage()."\n";
//    file_put_contents($file, $current);

    $err = array();
    $err['result'] = 'error';
    $err['message'] = 'Unknown error';
    $err['code'] = 0;

	//return OutputHandler::preRenderOutput(null, 'ERROR', $this->getMessage(), $this->getCode());
    echo json_encode(array(OutputHandler::preRenderOutput(null, 'ERROR', $exception->getMessage(), 0)));
}

//set_exception_handler('exception_handler');
