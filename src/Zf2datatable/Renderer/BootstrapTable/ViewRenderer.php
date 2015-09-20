<?php
namespace Zf2datatable\Renderer\BootstrapTable;

use Zf2datatable\Datagrid;
use Zf2datatable\Renderer\AbstractRenderer;
use Zend\Http\PhpEnvironment\Request as HttpRequest;

class ViewRenderer extends AbstractRenderer
{
    protected $isCrud = false;
    
    
    public function getName()
    {
        return 'bootstrapTable';
    }

    public function isExport()
    {
        return false;
    }

    public function isHtml()
    {
        return true;
    }

    /**
     *
     * @return HttpRequest
     */
    public function getRequest()
    {
        $request = parent::getRequest();
        if (! $request instanceof HttpRequest) {
            throw new \Exception('Request must be an instance of Zend\Http\PhpEnvironment\Request for HTML rendering');
        }

        return $request;
    }

    /**
     *
     * @see \ZfcDatagrid\Renderer\AbstractRenderer::getSortConditions()
     *
     * @return array
     */
    public function getSortConditions()
    {
		throw new \Exception('getSortConditionsNot implemeted');
    }

    /**
     *
     * @todo Make parameter config
     *
     * @see \ZfcDatagrid\Renderer\AbstractRenderer::getFilters()
     */
    public function getFilters()
    {
        throw new \Exception('getFilters not implemeted');
    }

    public function getCurrentPageNumber()
    {
        throw new \Exception('getCurrentPageNumber not implemeted');
    }
    
    /** get detail template **/
    public function getTemplate()
    {
        if ($this->template === null) {
            $this->template = $this->getTemplatePathDefault('detail');
        }
    
        return $this->template;
    }

    public function prepareViewModel(Datagrid $grid)
    {
        //parent::prepareViewModel($grid);

        $options = $this->getOptionsRenderer();
        $data 	   = $grid->getDataSource()->getDataDetail();
        $viewModel = $this->getViewModel();
        $mcvEvent  = $grid->getMvcEvent();
        
        $viewModel->setVariable('daterangeEnabled', false);
        $action=$mcvEvent->getRouteMatch()->getParam('action');
        $routeName = $mcvEvent->getRouteMatch()->getMatchedRouteName();
        $controller=$mcvEvent->getRouteMatch()->getParam('controller');
        $route = array("routeName"=>$routeName, "action"=>$action, "controller"=>$controller);
        //var_dump($route);
        $viewModel->setVariable('main_ruote', $route);
        $viewModel->setVariable('view_detail', $grid->getDataDetail());
      
    }

    public function execute()
    {
        $viewModel = $this->getViewModel();
        $viewModel->setTemplate($this->getTemplate());
        //$viewModel->setTemplate('zf2datatable/renderer/bootstrapTable/detail.phtml');
        return $viewModel;
    }
}
