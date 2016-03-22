<?php
namespace Extension\Symfony;

use Hal\Application\Extension\Reporter\Reporter;

class CliSummaryReport implements Reporter {
    /**
     * @var \StdClass
     */
    private $datas;

    /**
     * HtmlSummaryReport constructor.
     * @param array $datas
     */
    public function __construct(\StdClass $datas)
    {
        $this->datas = $datas;
    }


    public function render()
    {
        return '';
    }
}
