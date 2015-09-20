<?php
namespace Zf2datatableTest\Column;
use Zf2datatable\Filter;

class AbstractColumnTest extends \PHPUnit_Framework_TestCase
{

    protected $column;

    public function setUp(){
        $this->column = $this->getMockForAbstractClass('Zf2datatable\Column\AbstractColumn');
    }


    public function testGeneral()
    {
        $this->assertEquals(5,      $this->column->getWidth());
        $this->assertEquals(false,  $this->column->isHidden());
        $this->assertEquals(false,  $this->column->isIdentity());

        $this->assertInstanceOf('Zf2datatable\Column\Type\AbstractType', $this->column->getType());
        $this->assertInstanceOf('Zf2datatable\Column\Type\String', $this->column->getType());

        $this->assertEquals(false, $this->column->isTranslationEnabled());

        $this->column->setLabel('test');
        $this->assertEquals('test', $this->column->getLabel());

        $this->column->setUniqueId('unique_id');
        $this->assertEquals('unique_id', $this->column->getUniqueId());

        $this->column->setSelect('id', 'user');
        $this->assertEquals('id', $this->column->getSelectPart1());
        $this->assertEquals('user', $this->column->getSelectPart2());


        $this->column->setWidth(30);
        $this->assertEquals(30, $this->column->getWidth());
        $this->column->setWidth(50.53);
        $this->assertEquals(50.53, $this->column->getWidth());


        $this->column->setHidden(true);
        $this->assertEquals(true, $this->column->isHidden());
        $this->column->setHidden(false);
        $this->assertEquals(false, $this->column->isHidden());


        $col->setIdentity(true);
        $this->assertEquals(true, $col->isIdentity());
        $col->setIdentity(false);
        $this->assertEquals(false, $col->isIdentity());


    }

    public function testStyle()
    {
        $this->column = $this->getMockForAbstractClass('Zf2datatable\Column\AbstractColumn');
        $this->assertEquals(array(), $this->column->getStyles());
        $this->assertEquals(false, $this->column->hasStyles());

        $col->addStyle(new Style\Bold());
        $this->assertEquals(true, $col->hasStyles());
        $this->assertEquals(1, count($col->getStyles()));
        $style = $col->getStyles();
        $style = array_pop($style);
        $this->assertInstanceOf('Zf2datatable\Column\Style\Bold', $style);
        $this->assertInstanceOf('Zf2datatable\Column\Style\AbstractStyle', $style);

    }


    public function testType()
    {
        /* @var $col \ZfcDatagrid\Column\AbstractColumn */
        $this->column = $this->getMockForAbstractClass('ZfcDatagrid\Column\AbstractColumn');

        // DEFAULT
        $this->assertInstanceOf('ZfcDatagrid\Column\Type\String', $col->getType());

        $col->setType(new Type\PhpArray());
        $this->assertInstanceOf('Zf2datatable\Column\Type\AbstractType', $col->getType());
        $this->assertInstanceOf('Zf2datatable\Column\Type\PhpArray', $col->getType());

        $this->assertNull($col->getFormatter());
        $col->setType(new Type\Image());
        $this->assertInstanceOf('Zf2datatable\Column\Formatter\Image', $col->getFormatter());
    }


    public function testSort()
    {
        /* @var $col \ZfcDatagrid\Column\AbstractColumn */
        $this->col = $this->getMockForAbstractClass('Zf2datatable\Column\AbstractColumn');

        $this->assertEquals(true, $this->col->isUserSortEnabled());
        $this->assertEquals(false, $this->col->hasSortDefault());
        $this->assertEquals(array(), $this->col->getSortDefault());

        $this->assertEquals(false, $this->col->isSortActive());

        $this->col->setUserSortDisabled(true);
        $this->assertEquals(false, $this->col->isUserSortEnabled());
        $this->col->setUserSortDisabled(false);
        $this->assertEquals(true, $this->col->isUserSortEnabled());

        $this->col->setSortDefault(1, 'DESC');
        $this->assertEquals(array(
            'priority' => 1,
            'sortDirection' => 'DESC'
        ), $this->col->getSortDefault());
        $this->assertEquals(true, $this->col->hasSortDefault());

        $this->col->setSortActive('ASC');
        $this->assertEquals(true, $this->col->isSortActive());
        $this->assertEquals('ASC', $this->col->getSortActiveDirection());

        $this->col->setSortActive('DESC');
        $this->assertEquals(true, $this->col->isSortActive());
        $this->assertEquals('DESC', $this->col->getSortActiveDirection());
    }


    public function testFilter()
    {
        /* @var $col \ZfcDatagrid\Column\AbstractColumn */
        $col = $this->getMockForAbstractClass('Zf2datatable\Column\AbstractColumn');

        $this->assertEquals(true, $col->isUserFilterEnabled());

        $this->assertEquals(false, $col->hasFilterDefaultValue());

        $this->assertEquals(Filter::LIKE, $col->getFilterDefaultOperation());
        $this->assertEquals('', $col->getFilterDefaultValue());

        $this->assertEquals(false, $col->hasFilterSelectOptions());
        $this->assertEquals(null, $col->getFilterSelectOptions());

        $this->assertEquals(false, $col->isFilterActive());
        $this->assertEquals('', $col->getFilterActiveValue());

        $col->setUserFilterDisabled(true);
        $this->assertEquals(false, $col->isUserFilterEnabled());
        $col->setUserFilterDisabled(false);
        $this->assertEquals(true, $col->isUserFilterEnabled());

        $col->setFilterDefaultValue('!=blubb');
        $this->assertEquals(true, $col->hasFilterDefaultValue());
        $this->assertEquals('!=blubb', $col->getFilterDefaultValue());

        $col->setFilterDefaultOperation(Filter::GREATER_EQUAL);
        $this->assertEquals(Filter::GREATER_EQUAL, $col->getFilterDefaultOperation());

        $col->setFilterSelectOptions(array(
            1 => 'one',
            2 => 'two'
        ));
        $this->assertEquals(3, count($col->getFilterSelectOptions()));
        $this->assertEquals(true, $col->hasFilterSelectOptions());

        $col->setFilterSelectOptions(array(
            1 => 'one',
            2 => 'two'
        ), false);
        $this->assertEquals(2, count($col->getFilterSelectOptions()));
        $this->assertEquals(true, $col->hasFilterSelectOptions());

        $col->unsetFilterSelectOptions();
        $this->assertEquals(null, $col->getFilterSelectOptions());
        $this->assertEquals(false, $col->hasFilterSelectOptions());

        $col->setFilterActive('asdf');
        $this->assertEquals('asdf', $col->getFilterActiveValue());
        $this->assertEquals(true, $col->isFilterActive());
    }


    public function testSetGet()
    {
        /* @var $col \ZfcDatagrid\Column\AbstractColumn */
        $this->col = $this->getMockForAbstractClass('Zf2datatable\Column\AbstractColumn');

        $this->col->setTranslationEnabled(true);
        $this->assertEquals(true, $this->col->isTranslationEnabled());
        $this->col->setTranslationEnabled(false);
        $this->assertEquals(false, $this->col->isTranslationEnabled());

        $this->assertEquals(false, $this->col->hasReplaceValues());
        $this->assertEquals(array(), $this->col->getReplaceValues());
        $this->col->setReplaceValues(array(
            1,
            2,
            3
        ));
        $this->assertEquals(true, $this->col->hasReplaceValues());
        $this->assertEquals(array(
            1,
            2,
            3
        ), $this->col->getReplaceValues());
        $this->assertEquals(true, $this->col->notReplacedGetEmpty());
        $this->col->setReplaceValues(array(
            1,
            2,
            3
        ), false);
        $this->assertEquals(true, $this->col->hasReplaceValues());
        $this->assertEquals(array(
            1,
            2,
            3
        ), $this->col->getReplaceValues());
        $this->assertEquals(false, $this->col->notReplacedGetEmpty());

        $this->assertEquals(array(), $this->col->getRendererParameters('jqGrid'));

        $this->col->setRendererParameter('key', 'value', 'someRenderer');
        $this->assertEquals(array(
            'key' => 'value'
        ), $this->col->getRendererParameters('someRenderer'));
    }


    public function testFormatter()
    {
        /* @var $col \Zf2datagrid\Column\AbstractColumn */
        $this->col = $this->getMockForAbstractClass('Zf2datagrid\Column\AbstractColumn');

        // DEFAULT
        $this->assertNull($this->col->getFormatter());
        $this->assertFalse($this->col->hasFormatter());

        $this->col->setFormatter(new Formatter\Link());
        $this->assertTrue($this->col->hasFormatter());
        $this->assertInstanceOf('Zf2datagrid\Column\Formatter\AbstractFormatter', $this->col->getFormatter());
        $this->assertInstanceOf('Zf2datagrid\Column\Formatter\Link', $this->col->getFormatter());
    }

    public function testRowClick()
    {
        /* @var $col \Zf2datagrid\Column\AbstractColumn */
        $this->col = $this->getMockForAbstractClass('Zf2datagrid\Column\AbstractColumn');


        $this->assertTrue($this->col->isRowClickEnabled());

        $this->col->setRowClickDisabled(true);
        $this->assertFalse($this->col->isRowClickEnabled());

        $this->col->setRowClickDisabled(false);
        $this->assertTrue($this->col->isRowClickEnabled());
    }




    public function tearDown(){

    }
}

?>