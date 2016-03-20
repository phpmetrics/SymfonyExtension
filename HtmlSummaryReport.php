<?php
namespace Extension\Symfony2;

use Hal\Application\Extension\Reporter\ReporterHtmlSummary;

class HtmlSummaryReport implements ReporterHtmlSummary {
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


    public function renderJs()
    {
        return "document.getElementById('link-symfony2').onclick = function() { displayTab(this, 'symfony2')};";
    }

    public function renderHtml()
    {
        $html = <<<EOT
<div class="tab" id="symfony2">

    <div class="row">
        <h3>Symfony</h3>
        <p>
            Version: {$this->datas->symfony2->version}
        </p>
    </div>

    <div class="row">
        <div class="col-3">
            <h3>Controllers <small>({$this->datas->controllers->total})</small></h3>
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td>Average complexity</td>
                        <td>{$this->datas->controllers->ccn}</td>
                    </tr>
                    <tr>
                        <td>Average Logical lines of code</td>
                        <td>{$this->datas->controllers->lloc}</td>
                    </tr>
                    <tr>
                        <td>Average Maintainability</td>
                        <td>{$this->datas->controllers->mi}</td>
                    </tr>
                    <tr>
                        <td>Methods by class</td>
                        <td>{$this->datas->controllers->methods}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-3">
            <h3>Forms <small>({$this->datas->forms->total})</small></h3>
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td>Average complexity</td>
                        <td>{$this->datas->forms->ccn}</td>
                    </tr>
                    <tr>
                        <td>Average Logical lines of code</td>
                        <td>{$this->datas->forms->lloc}</td>
                    </tr>
                    <tr>
                        <td>Average Maintainability</td>
                        <td>{$this->datas->forms->mi}</td>
                    </tr>
                    <tr>
                        <td>Methods by class</td>
                        <td>{$this->datas->forms->methods}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-3">
            <h3>Commands <small>({$this->datas->commands->total})</small></h3>
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td>Average complexity</td>
                        <td>{$this->datas->commands->ccn}</td>
                    </tr>
                    <tr>
                        <td>Average Logical lines of code</td>
                        <td>{$this->datas->commands->lloc}</td>
                    </tr>
                    <tr>
                        <td>Average Maintainability</td>
                        <td>{$this->datas->commands->mi}</td>
                    </tr>
                    <tr>
                        <td>Methods by class</td>
                        <td>{$this->datas->commands->methods}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-3">
            <h3>Twig <small>({$this->datas->twig->total})</small></h3>
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td>HTML</td>
                        <td>{$this->datas->twig->html}</td>
                    </tr>
                    <tr>
                        <td>JavaScript</td>
                        <td>{$this->datas->twig->js}</td>
                    </tr>
                    <tr>
                        <td>CSS</td>
                        <td>{$this->datas->twig->css}</td>
                    </tr>
                    <tr>
                        <td>Other</td>
                        <td>{$this->datas->twig->other}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="col-3">
            <h3>Misc</h3>
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td>Events</td>
                        <td>{$this->datas->misc->events}</td>
                    </tr>
                    <tr>
                        <td>Param Converters</td>
                        <td>{$this->datas->misc->paramconverters}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
EOT;
        return $html;
    }

    public function getMenus()
    {
        return array(
            'symfony2' => 'Symfony2'
        );
    }
}
