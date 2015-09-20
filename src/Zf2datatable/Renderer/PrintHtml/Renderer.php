<?php
namespace Zf2datatable\Renderer\PrintHtml;

use Zf2datatable\Renderer\AbstractRenderer;
use Zend\View\Model\ViewModel;

class Renderer extends AbstractRenderer
{

    public function getName()
    {
        return 'printHtml';
    }

    public function isExport()
    {
        return true;
    }

    public function isHtml()
    {
        return true;
    }

    /**
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function execute()
    {
        $layout = $this->getViewModel();
        $layout->setTemplate($this->getTemplate());
        $layout->setTerminal(true);

        $table = new ViewModel();
        $table->setTemplate('zf2datatable/renderer/printHtml/table');
        $table->setVariables($layout->getVariables());

        $layout->addChild($table, 'table');

        return $layout;

        // $viewModel->setTemplate('zfc-datagrid/renderer/printHtml/layout');

        // $viewChild = new ViewModel();
        // $viewChild->setVariables($viewModel->getVariables());
        // $viewChild->setTemplate($this->getTemplate());

        // $viewModel->addChild($viewChild, 'table');

        // return $viewModel;
    }
}
