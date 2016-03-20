<?php
namespace Extension\Symfony2;

use Hal\Application\Config\Configuration;
use Hal\Component\Bounds\Bounds;
use Hal\Component\File\Finder;
use Hal\Component\Result\ResultCollection;

require_once __DIR__ . '/CliSummaryReport.php';
require_once __DIR__ . '/HtmlSummaryReport.php';

class Symfony2Extension implements \Hal\Application\Extension\Extension {

    /**
     * @var array
     */
    private $datas = array();

    /**
     * @param Configuration $config
     * @param ResultCollection $collection
     * @param ResultCollection $aggregatedResults
     * @param Bounds $bounds
     * @return mixed
     */
    public function receive(Configuration $config, ResultCollection $collection, ResultCollection $aggregatedResults, Bounds $bounds)
    {

        $this->datas = (object) array(
            'symfony2' => (object) array(
                'version' => '?'
            )
            ,'controllers' => (object) array(
                'total' => 0
                , 'ccn' => 0
                , 'mi' => 0
                , 'lloc' => 0
                , 'methods' => 0
            )
            , 'forms' => (object) array(
                'total' => 0
                , 'ccn' => 0
                , 'mi' => 0
                , 'lloc' => 0
                , 'methods' => 0
            )
            ,'commands' => (object) array(
                'total' => 0
                , 'ccn' => 0
                , 'mi' => 0
                , 'lloc' => 0
                , 'methods' => 0
            )
            ,'misc' => (object) array(
                'paramconverters' => 0
                , 'events' => 0
            )
            ,'twig' => (object) array(
                'total' => 0
                ,'js' => 0
                ,'html' => 0
                ,'css' => 0
                ,'other' => 0
            )
        );
        $ccnControllers = $llocControllers = $miControllers = $methodsControllers = array();
        $ccnForms = $llocForms = $miForms = $methodsForms = array();
        $ccnCommands = $llocCommands = $miCommands = $methodsCommands= array();

        // search controller
        foreach($collection as $f => $item) {
            $classes = $item->getOop()->getClasses();
            foreach($classes as $class) {

                // search controllers
                if('Symfony\Bundle\FrameworkBundle\Controller\Controller' == $class->getParent()
                ||\preg_match('/Controller$/', $class->getName())) {
                    $this->datas->controllers->total++;
                    $ccnControllers[$f] = $item->getMcCabe()->getCyclomaticComplexityNumber();
                    $llocControllers[$f] = $item->getLoc()->getLogicalLoc();
                    $miControllers[$f] = $item->getMaintainabilityIndex()->getMaintainabilityIndex();
                    $methodsControllers[$f] = sizeof($class->getMethods());
                }

                // search commands
                if('Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand' == $class->getParent()
                    ||\preg_match('/Command$/', $class->getName())) {
                    $this->datas->commands->total++;
                    $ccnCommands[$f] = $item->getMcCabe()->getCyclomaticComplexityNumber();
                    $llocCommands[$f] = $item->getLoc()->getLogicalLoc();
                    $miCommands[$f] = $item->getMaintainabilityIndex()->getMaintainabilityIndex();
                    $methodsCommands[$f] = sizeof($class->getMethods());
                }

                // search forms
                if('Symfony\Component\Form\AbstractType' == $class->getParent()
                    ||\preg_match('/Type/', $class->getName())) {
                    $this->datas->forms->total++;
                    $ccnForms[$f] = $item->getMcCabe()->getCyclomaticComplexityNumber();
                    $llocForms[$f] = $item->getLoc()->getLogicalLoc();
                    $miForms[$f] = $item->getMaintainabilityIndex()->getMaintainabilityIndex();
                    $methodsForms[$f] = sizeof($class->getMethods());
                }

                // search params converters
                if(in_array('Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface', $class->getInterfaces())) {
                    $this->datas->misc->paramconverters++;
                }

                // search events
                if('Symfony\Component\EventDispatcher\Event' == $class->getParent()) {
                    $this->datas->misc->events++;
                }
            }
        }

        // twig
        $finder = new Finder('twig', $config->getPath()->getExcludedDirs());
        $files = $finder->find($config->getPath()->getBasePath());
        $this->datas->twig->total = sizeof($files);
        foreach($files as $filename) {
            switch(true) {
                case preg_match('/\.html\.twig$/i', $filename):
                    $this->datas->twig->html++;
                    break;
                case preg_match('/\.js\.twig$/i', $filename):
                    $this->datas->twig->js++;
                    break;
                case preg_match('/\.css\.twig$/i', $filename):
                    $this->datas->twig->css++;
                    break;
                default:
                    $this->datas->twig->other++;
                    break;
            }
        }

        // search Symfony2 version
        $finder = new Finder('cache', $config->getPath()->getExcludedDirs());
        $files = $finder->find($config->getPath()->getBasePath());
        foreach($files as $filename) {
            if(preg_match('/bootstrap\.php\.cache/', $filename)) {
                $content = file_get_contents($filename);
                if(preg_match("/const VERSION ='(.*?)';/", $content, $matches)) {
                    list(, $sfVersion) = $matches;
                    $this->datas->symfony2->version = $sfVersion;
                }
                break;
            }
        }

        // averages
        $this->datas->controllers->ccn = round(array_sum($ccnControllers) / sizeof($ccnControllers), 2);
        $this->datas->controllers->mi = round(array_sum($miControllers) / sizeof($miControllers), 2);
        $this->datas->controllers->lloc = round(array_sum($llocControllers) / sizeof($llocControllers), 0);
        $this->datas->controllers->methods = round(array_sum($methodsControllers) / sizeof($methodsControllers), 1);
        $this->datas->forms->ccn = round(array_sum($ccnForms) / sizeof($ccnForms), 2);
        $this->datas->forms->mi = round(array_sum($miForms) / sizeof($miForms), 2);
        $this->datas->forms->lloc = round(array_sum($llocForms) / sizeof($llocForms), 0);
        $this->datas->forms->methods = round(array_sum($methodsForms) / sizeof($methodsForms), 1);
        $this->datas->commands->ccn = round(array_sum($ccnCommands) / sizeof($ccnCommands), 2);
        $this->datas->commands->mi = round(array_sum($miCommands) / sizeof($miCommands), 2);
        $this->datas->commands->lloc = round(array_sum($llocCommands) / sizeof($llocCommands), 0);
        $this->datas->commands->methods = round(array_sum($methodsCommands) / sizeof($methodsCommands), 1);

    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Symfony 2';
    }

    public function getReporterHtmlSummary()
    {
        return new HtmlSummaryReport($this->datas);
    }

    public function getReporterCliSummary()
    {
        return new CliSummaryReport($this->datas);
    }

}


return new Symfony2Extension();