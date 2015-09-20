<?php
namespace Zf2datatable\Examples\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zf2datatable\Column;

class MinimalController extends AbstractActionController
{

    /**
     * Simple bootstrap table
     *
     * @return \ZfcDatagrid\Controller\ViewModel
     */
    public function bootstrapAction()
    {
        $row = array(
            'displayName' => 'Wayne? John!',
        );
        $row2 = array(
            'displayName' => 'Name2',
        );

        $data = array(
            $row,
            $row2
        );

        /* @var $grid \ZfcDatagrid\Datagrid */
        $grid = $this->getServiceLocator()->get('Zf2datatable\Datagrid');
        $grid->setTitle('Minimal grid');

        //you can change here the renderer (allowed: bootstrapTable / jqgrid...default is bootstrapTable
        $grid->setRendererName('jqGrid');
        $grid->setDataSource($data);

        $col = new Column\Select('displayName');
        $col->setLabel('Name');
        $grid->addColumn($col);

        $grid->render();

        return $grid->getResponse();
    }
}
