<?php
namespace Zf2datatableTest\Column\Action;
use Zf2datatable\Filter;

/**
 * @group Column
 * @covers Zf2datatable\Column\Action\AbstractAction
 */

class AbstractActionTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var \Zf2datatable\Column\AbstractColumn
     */
    private $column;
    
    public function setUp()
    {
        $this->column = $this->getMockForAbstractClass('Zf2datatable\Column\AbstractColumn');
        $this->column->setUniqueId('colName');
    }
    
    public function testLink()
    {
        /* @var $action \ZfcDatagrid\Column\Action\AbstractAction */
        $action = $this->getMockForAbstractClass('Zf2datatable\Column\Action\AbstractAction');
    
        $this->assertEquals('#', $action->getLink());
        $this->assertEquals('#', $action->getAttribute('href'));
    
        $action->setLink('/my/page/is/cool');
        $this->assertEquals('/my/page/is/cool', $action->getLink());
    }
    
    
    public function testLinkPlaceholder()
    {
        /* @var $action \ZfcDatagrid\Column\Action\AbstractAction */
        $action = $this->getMockForAbstractClass('Zf2datatable\Column\Action\AbstractAction');
    
        $action->setLink('/myLink/id/' . $action->getRowIdPlaceholder());
        $this->assertEquals('/myLink/id/:rowId:', $action->getLink());
    
        $this->assertEquals('/myLink/id/3', $action->getLinkReplaced(array(
            'idConcated' => 3
        )));
    
        // Column
        $column = $this->getMockForAbstractClass('Zf2datatable\Column\AbstractColumn');
        $column->setUniqueId('myCol');
    
        $action->setLink('/myLink/para1/' . $action->getColumnValuePlaceholder($column));
        $this->assertEquals('/myLink/para1/:myCol:', $action->getLink());
    
        $this->assertEquals('/myLink/para1/someValue', $action->getLinkReplaced(array(
            'myCol' => 'someValue'
        )));
    }
    
    
    
    
    
}

?>