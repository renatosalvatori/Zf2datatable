<?php
namespace Zf2datatable\Renderer\BootstrapTable;

use Zf2datatable\Datagrid;
use Zf2datatable\Renderer\AbstractRenderer;
use Zend\Http\PhpEnvironment\Request as HttpRequest;

class Renderer extends AbstractRenderer
{

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
     * @see \Zf2datatable\Renderer\AbstractRenderer::getSortConditions()
     *
     * @return array
     */
    public function getSortConditions()
    {
        if (is_array($this->sortConditions)) {
            // set from cache! (for export)
            return $this->sortConditions;
        }

        $request = $this->getRequest();

        $optionsRenderer = $this->getOptionsRenderer();
        $parameterNames = $optionsRenderer['parameterNames'];

        $sortConditions = array();
        $sortColumns = $request->getPost($parameterNames['sortColumns'], $request->getQuery($parameterNames['sortColumns']));
        $sortDirections = $request->getPost($parameterNames['sortDirections'], $request->getQuery($parameterNames['sortDirections']));
        if ($sortColumns != '') {
            $sortColumns = explode(',', $sortColumns);
            $sortDirections = explode(',', $sortDirections);

            if (count($sortColumns) != count($sortDirections)) {
                throw new \Exception('Count missmatch order columns/direction');
            }

            foreach ($sortColumns as $key => $sortColumn) {
                $sortDirection = strtoupper($sortDirections[$key]);

                if ($sortDirection != 'ASC' && $sortDirection != 'DESC') {
                    $sortDirection = 'ASC';
                }

                foreach ($this->getColumns() as $column) {
                    /* @var $column \Zf2datatable\Column\AbstractColumn */
                    if ($column->getUniqueId() == $sortColumn) {

                        if($column->getFormatter() instanceof \Zf2datatable\Column\Formatter\Callback){
                           $columnSort = clone $column;
                           $columnSort->unsetFormatter();

                            $sortConditions[] = array(
                                'sortDirection' => $sortDirection,
                                'column' => $columnSort
                            );
                        }
                        else{
                            $sortConditions[] = array(
                                'sortDirection' => $sortDirection,
                                'column' => $column
                            );
                        }

                        $column->setSortActive($sortDirection);
                    }
                }
            }
        }

        if (count($sortConditions) > 0) {
            $this->sortConditions = $sortConditions;
        } else {
            // No user sorting -> get default sorting
            $this->sortConditions = $this->getSortConditionsDefault();
        }

        return $this->sortConditions;
    }

    /**
     *
     * @todo Make parameter config
     *
     * @see \Zf2datatable\Renderer\AbstractRenderer::getFilters()
     */
    public function getFilters($keepCache=false)
    {
        $request = $this->getRequest();
        $filters = array();

        if (is_array($this->filters)) {
            return $this->filters;
        }

        if ($keepCache) {
            // Export renderer should always retrieve the filters from cache!
            $cacheData = $this->getCacheData();

            if (! isset($cacheData['filters'])) {
                throw new \Exception('Filters from cache are missing!');
            }


           foreach ($this->getColumns() as $column) {
                foreach ($cacheData['filters'] as $filter){
                   if( $column->getUniqueId() == $filter->getColumn()->getUniqueId() )
                            $column->setFilterActive($filter->getDisplayColumnValue());

                }
            }

            $this->filters =  $cacheData['filters'];
            return $this->filters;
        }



        if ($request->isPost() === true && $request->getPost('toolbarFilters') !== null) {
            foreach ($request->getPost('toolbarFilters') as $uniqueId => $value) {
                if ($value != '') {
                    foreach ($this->getColumns() as $column) {
                        /* @var $column \Zf2datatable\Column\AbstractColumn */

                        if ($column->getUniqueId() == $uniqueId) {


                            if($column->getFormatter() instanceof \Zf2datatable\Column\Formatter\Callback){
                                $columnFilter = clone $column;
                                $columnFilter->unsetFormatter();

                                $filter = new \Zf2datatable\Filter();
                                $filter->setFromColumn($columnFilter, $value);
                                $filters[] = $filter;
                                $column->setFilterActive($filter->getDisplayColumnValue());
                            }
                            else{
                            $filter = new \Zf2datatable\Filter();
                            $filter->setFromColumn($column, $value);
                            $filters[] = $filter;
                            $column->setFilterActive($filter->getDisplayColumnValue());
                            }
                        }
                    }
                }
            }

        }


        if ($request->isGet() === true && $request->getQuery('toolbarFilters') !== null) {
            foreach ($request->getQuery('toolbarFilters') as $uniqueId => $value) {
                if ($value != '') {
                    foreach ($this->getColumns() as $column) {
                        /* @var $column \Zf2datatable\Column\AbstractColumn */
                        if ($column->getUniqueId() == $uniqueId) {

                            if($column->getFormatter() instanceof \Zf2datatable\Column\Formatter\Callback){
                                $columnFilter = clone $column;
                                $columnFilter->unsetFormatter();
                                $filter = new \Zf2datatable\Filter();
                                $filter->setFromColumn($columnFilter, $value);
                                $filters[] = $filter;
                                $column->setFilterActive($filter->getDisplayColumnValue());
                            }
                            else{
                                $filter = new \Zf2datatable\Filter();
                                $filter->setFromColumn($column, $value);
                                $filters[] = $filter;
                                $column->setFilterActive($filter->getDisplayColumnValue());
                            }
                        }
                    }
                }
            }

            $this->redirectToGet = true;
           //return $this->redirectAfterSetFilter();
        }


        //


        if (count($filters) > 0) {
            $this->filters = $filters;
        } else {
            // No user sorting -> get default sorting
            $this->filters = $this->getFiltersDefault();
        }

        return $this->filters;
    }



    protected  function redirectAfterSetFilter(){

        $plgmanager = $this->getDataGrid()->getPluginControllerManager();
        $e = $this->getDataGrid()->getMvcEvent();

        $controller = substr( $e->getRouteMatch()->getParam('controller'), strrpos($e->getRouteMatch()->getParam('controller'), '\\') + 1);
        $action = $e->getRouteMatch()->getParam('action');
        $routeName = $e->getRouteMatch()->getMatchedRouteName();

        $e->stopPropagation(true);
        $response = $e->getResponse();
        $url = $e->getRouter()->assemble(array('controller' => $controller, 'action' => $action),array('name'=>$routeName));
        $plgredirect = $plgmanager->get('redirect');
        return $plgredirect->toUrl($url.'?keepCache=1');
    }



    public function getCurrentPageNumber()
    {
        $optionsRenderer = $this->getOptionsRenderer();
        $parameterNames = $optionsRenderer['parameterNames'];

        if ($this->getRequest() instanceof HttpRequest) {
            $this->currentPageNumber = (int) $this->getRequest()->getPost($parameterNames['currentPage'], $this->getRequest()
                ->getQuery($parameterNames['currentPage'], 1));
        }

        return (int) $this->currentPageNumber;
    }

    public function prepareViewModel(Datagrid $grid)
    {
        parent::prepareViewModel($grid);

        $options = $this->getOptionsRenderer();

        $viewModel = $this->getViewModel();

        // Check if the datarange picker is enabled
        if (isset($options['daterange']['enabled']) && $options['daterange']['enabled'] === true) {
            // load js and stylesheet
            $basePath = $grid->getPluginViewHelperManager()->get('BasePath');
            $grid->getPluginViewHelperManager()->get('HeadLink')->appendStylesheet($basePath->__invoke().'/css/daterangepicker-bs3.css');
            $grid->getPluginViewHelperManager()->get('HeadScript')->appendFile($basePath->__invoke().'/js/moment.js');
            $grid->getPluginViewHelperManager()->get('HeadScript')->appendFile($basePath->__invoke().'/js/daterangepicker.js');
            $dateRangeParameters = $options['daterange']['options'];
            $viewModel->setVariable('daterangeEnabled', true);
            $viewModel->setVariable('additionalButton', $grid->getAdditionalButton());
            $viewModel->setVariable('daterangeParameters', $dateRangeParameters);
            $viewModel->setVariable('twuitterVersion', $grid->getTwitterBoostrapVersion());
            $viewModel->setVariable('uriRedirectJson',$grid->getDefaultUriRedirect());
            $viewModel->setVariable('additionalFilters',$grid->getAdditionalFilter());
        } else {
            $viewModel->setVariable('daterangeEnabled', false);
        }
        $viewModel->setVariable('isAllowAddCrud', $grid->getisAllowAdd());
        $viewModel->setVariable('successMessages', $grid->getPluginControllerManager()->get( 'flashmessenger' )->getSuccessMessages());
        $viewModel->setVariable('errorMessages', $grid->getPluginControllerManager()->get( 'flashmessenger' )->getErrorMessages());
        $viewModel->setVariable('injectedJsCode', $grid->getInjetJsCode());


        $childModel = $grid->getViewChild();
        if($childModel['ViewModel'] instanceof \Zend\View\Model\ViewModel){
                $viewModel->addChild($childModel['ViewModel'], $childModel['Position']);
        }


    }

    public function execute()
    {
        $viewModel = $this->getViewModel();
        $viewModel->setTemplate($this->getTemplate());


        if($this->redirectToGet)
            return $this->redirectAfterSetFilter();


        return $viewModel;
    }
}
