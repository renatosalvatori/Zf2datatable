<?php
namespace Zf2datatable\Renderer\BootstrapTable;

use Zf2datatable\Datagrid;
use Zf2datatable\Renderer\AbstractRenderer;
use Zend\Http\PhpEnvironment\Request as HttpRequest;

class FormRenderer extends AbstractRenderer
{
	protected $formCrud;

	protected $isCrud = true;

	/**
	 * @return the $formCrud
	 */
	public function getFormCrud() {
		return $this->formCrud;
	}

	/**
	 * @param field_type $formCrud
	 */
	public function setFormCrud($formCrud) {
		$this->formCrud = $formCrud;
	}

	public function getName()
    {
        //return 'bootstrapTableFormCrud';
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
        return $this->sortConditions;
    }


    public function getCurrentPageNumber()
    {
        return (int) $this->currentPageNumber;
    }


    /** get detail template **/
    public function getTemplate()
    {
        if ($this->template === null) {
            $this->template = $this->getTemplatePathDefault('formcrud');
        }

        return $this->template;
    }



    public function prepareViewModel(Datagrid $grid)
    {
        parent::prepareViewModel($grid);

        $options = $this->getOptionsRenderer();
        $viewModel = $this->getViewModel();
        $mcvEvent  = $grid->getMvcEvent();
        //$routeMatch = $mvcEvent->getRouteMatch(); getMatchedRouteName
        $controller=$mcvEvent->getRouteMatch()->getParam('controller');
        $action=$mcvEvent->getRouteMatch()->getParam('action');
        $routeName = $mcvEvent->getRouteMatch()->getMatchedRouteName();
        $route = array("routeName"=>$routeName, "action"=>$action, "controller"=>$controller);
        //var_dump($route);
        $viewModel->setVariable('main_ruote', $route);

        $viewModel->setVariable('jsCodeElement', $this->getJsCodeElement($grid));
        $viewModel->setVariable('crudform', $grid->getFrmMainCrud());
        $viewModel->setVariable('injectedJsFormCode', $grid->getInjetJsFormCode());

    }

    protected function getJsCodeElement($grid){
        $jsString = "";
        foreach($grid->getFrmMainCrud() as $element){

            if($element instanceof \Zf2datatable\Form\Element\DateCalendar){
                //secho $element->getAttribute('jsOption');
                //$jsString.="$('#{$element->getAttribute('id')}').datetimepicker({".$element->getAttribute('jsOption')."}); \n";
                $jsString.="$('#{$element->getName()}').datetimepicker({".$element->getAttribute('jsOption')."}); \n";
            }

            if($element instanceof \Zf2datatable\Form\Element\DateTimeCalendar){
                //secho $element->getAttribute('jsOption');
                //$jsString.="$('#{$element->getAttribute('id')}').datetimepicker({".$element->getAttribute('jsOption')."}); \n";
                $jsString.="$('#{$element->getName()}').datetimepicker({".$element->getAttribute('jsOption')."}); \n";
            }
        }

        return $jsString;
    }

    public function execute()
    {
        $viewModel = $this->getViewModel();
        //$viewModel->setTemplate('zf2datatable/renderer/bootstrapTable/formcrud.phtml');
        $viewModel->setTemplate($this->getTemplate());

        return $viewModel;
    }
}
