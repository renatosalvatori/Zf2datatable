<?php
namespace Zf2datatableTest\Renderer;

use Zf2datatable\Filter;
use Zf2datatable\Column\Style;
use PHPUnit_Framework_TestCase;
use Zend\Paginator;
use ReflectionClass;

/**
 * @group Renderer
 * @covers Zf2datatable\Renderer\AbstractRenderer
 */
class AbstractRendererTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @var \Zf2datatable\Column\AbstractColumn
     */
    private $colMock;

    public function setUp()
    {
        $this->colMock = $this->getMockForAbstractClass('Zf2datatable\Column\AbstractColumn');
    }

    public function testOptions()
    {
        $renderer = $this->getMockForAbstractClass('Zf2datatable\Renderer\AbstractRenderer');
        $renderer->setOptions(array(
            'test'
        ));

        $this->assertEquals(array(
            'test'
        ), $renderer->getOptions());
    }

    public function testRendererOptions()
    {
        $renderer = $this->getMockForAbstractClass('Zf2datatable\Renderer\AbstractRenderer');
        $renderer->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('abstract'));

        $this->assertEquals(array(), $renderer->getOptionsRenderer());

        $renderer->setOptions(array(
            'renderer' => array(
                'abstract' => array(
                    'test'
                )
            )
        ));

        $this->assertEquals(array(
            'test'
        ), $renderer->getOptionsRenderer());
    }

    public function testViewModel()
    {
        $renderer = $this->getMockForAbstractClass('Zf2datatable\Renderer\AbstractRenderer');

        $this->assertNull($renderer->getViewModel());

        $viewModel = $this->getMock('Zend\View\Model\ViewModel');
        $renderer->setViewModel($viewModel);
        $this->assertSame($viewModel, $renderer->getViewModel());
    }

    public function testTemplate()
    {
        $renderer = $this->getMockForAbstractClass('Zf2datatable\Renderer\AbstractRenderer');
        $renderer->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('abstract'));

        $this->assertEquals('zf2datatable/renderer/abstract/layout', $renderer->getTemplate());
        $this->assertEquals('zf2datatable/toolbar/toolbar', $renderer->getToolbarTemplate());

        $renderer->setTemplate('blubb/layout');
        $this->assertEquals('blubb/layout', $renderer->getTemplate());

        $renderer->setToolbarTemplate('blubb/toolbar');
        $this->assertEquals('blubb/toolbar', $renderer->getToolbarTemplate());
    }

    public function testTemplateConfig()
    {
        $renderer = $this->getMockForAbstractClass('Zf2datatable\Renderer\AbstractRenderer');
        $renderer->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('abstract'));

        $renderer->setOptions(array(
            'renderer' => array(
                'abstract' => array(
                    'templates' => array(
                        'layout' => 'config/my/template',
                        'toolbar' => 'config/my/toolbar'
                    )
                )
            )
        ));

        $this->assertEquals('config/my/template', $renderer->getTemplate());
        $this->assertEquals('config/my/toolbar', $renderer->getToolbarTemplate());
    }

    public function testPaginator()
    {
        $renderer = $this->getMockForAbstractClass('Zf2datatable\Renderer\AbstractRenderer');

        $this->assertNull($renderer->getPaginator());

        $testCollection = range(1, 101);
        $pagintorMock = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($testCollection));
        $renderer->setPaginator($pagintorMock);

        $this->assertSame($pagintorMock, $renderer->getPaginator());
    }

    public function testColumns()
    {
        $renderer = $this->getMockForAbstractClass('Zf2datatable\Renderer\AbstractRenderer');

        $this->assertEquals(array(), $renderer->getColumns());

        $col = clone $this->colMock;
        $renderer->setColumns(array(
            $col
        ));

        $this->assertEquals(array(
            $col
        ), $renderer->getColumns());
    }

    public function testRowStyles()
    {
        /* @var $renderer \Zf2datatable\Renderer\AbstractRenderer */
        $renderer = $this->getMockForAbstractClass('Zf2datatable\Renderer\AbstractRenderer');

        $this->assertEquals(array(), $renderer->getRowStyles());

        $bold = new Style\Bold();
        $renderer->setRowStyles(array(
            $bold
        ));
        $this->assertEquals(array(
            $bold
        ), $renderer->getRowStyles());
    }

    public function testCalculateColumnWidthPercent()
    {
        /* @var $renderer \Zf2datatable\Renderer\AbstractRenderer */
        $renderer = $this->getMockForAbstractClass('Zf2datatable\Renderer\AbstractRenderer');

        $reflection = new ReflectionClass(get_class($renderer));
        $method = $reflection->getMethod('calculateColumnWidthPercent');
        $method->setAccessible(true);

        $col1 = clone $this->colMock;
        $cols = array(
            $col1
        );

        /*
         * Width lower than 100%
         */
        $this->assertEquals(5, $col1->getWidth());
        $method->invokeArgs($renderer, array(
            $cols
        ));
        $this->assertEquals(100, $col1->getWidth());

        /*
         * Width higher than 100%
         */
        $col1 = clone $this->colMock;
        $col1->setWidth(90);

        $col2 = clone $this->colMock;
        $col2->setWidth(60);
        $cols = array(
            $col1,
            $col2
        );

        $method->invokeArgs($renderer, array(
            $cols
        ));
        $this->assertEquals(60, $col1->getWidth());
        $this->assertEquals(40, $col2->getWidth());
    }

    public function testData()
    {
        /* @var $renderer \Zf2datatable\Renderer\AbstractRenderer */
        $renderer = $this->getMockForAbstractClass('Zf2datatable\Renderer\AbstractRenderer');

        $this->assertEquals(array(), $renderer->getData());

        $data = array(
            array(
                'myCol' => 123
            )
        );
        $renderer->setData($data);
        $this->assertEquals($data, $renderer->getData());
    }

    public function testCacheData()
    {
        /* @var $renderer \Zf2datatable\Renderer\AbstractRenderer */
        $this->markTestIncomplete();
        $renderer = $this->getMockForAbstractClass('Zf2datatable\Renderer\AbstractRenderer');

        $this->assertEquals(array(), $renderer->getCacheData());

        $data = array(
            'sortConditions' => '',
            'filters' => '',
            'currentPage' => 123,
            'data' => array(
                array(
                    'myCol' => 123
                )
            )
        );
        $renderer->setCacheData($data);
        $this->assertEquals($data, $renderer->getCacheData());
    }

    public function testMvcEvent()
    {
        /* @var $renderer \Zf2datatable\Renderer\AbstractRenderer */
        $renderer = $this->getMockForAbstractClass('Zf2datatable\Renderer\AbstractRenderer');

        $request = $this->getMock('Zend\Http\Request', array(), array(), '', false);

        $mvcEvent = $this->getMock('Zend\Mvc\MvcEvent', array(), array(), '', false);
        $mvcEvent->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request));

        $this->assertNull($renderer->getMvcEvent());
        $renderer->setMvcEvent($mvcEvent);
        $this->assertSame($mvcEvent, $renderer->getMvcEvent());

        // request
        $this->assertSame($request, $renderer->getRequest());
    }

    public function testTranslator()
    {
        /* @var $renderer \Zf2datatable\Renderer\AbstractRenderer */
        $renderer = $this->getMockForAbstractClass('Zf2datatable\Renderer\AbstractRenderer');

        $translator = $this->getMock('Zend\I18n\Translator\Translator', array(), array(), '', false);

        $this->assertNull($renderer->getTranslator());
        $renderer->setTranslator($translator);
        $this->assertSame($translator, $renderer->getTranslator());
    }

    public function testTitle()
    {
        /* @var $renderer \Zf2datatable\Renderer\AbstractRenderer */
        $renderer = $this->getMockForAbstractClass('Zf2datatable\Renderer\AbstractRenderer');

        $this->assertNull($renderer->getTitle());

        $renderer->setTitle('My title');
        $this->assertEquals('My title', $renderer->getTitle());
    }

    public function testCacheId()
    {
        /* @var $renderer \Zf2datatable\Renderer\AbstractRenderer */
        $renderer = $this->getMockForAbstractClass('Zf2datatable\Renderer\AbstractRenderer');

        $this->assertNull($renderer->getCacheId());

        $renderer->setCacheId('a_cache_id');
        $this->assertEquals('a_cache_id', $renderer->getCacheId());
    }

    public function testGetSortConditionsSortEmpty()
    {
        /* @var $renderer \Zf2datatable\Renderer\AbstractRenderer */
        $renderer = $this->getMockForAbstractClass('Zf2datatable\Renderer\AbstractRenderer');

        // no sorting
        $sortConditions = $renderer->getSortConditions();
        $this->assertEquals(array(), $sortConditions);

        // 2nd call -> from array
        $sortConditions = $renderer->getSortConditions();
        $this->assertEquals(array(), $sortConditions);
    }

    public function testGetSortConditionsSortDefault()
    {
        /* @var $renderer \Zf2datatable\Renderer\AbstractRenderer */
        $renderer = $this->getMockForAbstractClass('Zf2datatable\Renderer\AbstractRenderer');

        $col1 = clone $this->colMock;
        $col1->setUniqueId('myCol');
        $col1->setSortDefault(1);
        $renderer->setColumns(array(
            $col1
        ));

        $sortConditions = $renderer->getSortConditions();
        $this->assertEquals(array(
            1 => array(
                'column' => $col1,
                'sortDirection' => 'ASC'
            )
        ), $sortConditions);
    }

    public function testGetFiltersDefault()
    {
        $request = $this->getMock('Zend\Http\PhpEnvironment\Request', array(), array(), '', false);
        $request->expects($this->any())
            ->method('isPost')
            ->will($this->returnValue(false));

        $mvcEvent = $this->getMock('Zend\Mvc\MvcEvent', array(), array(), '', false);
        $mvcEvent->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request));

        /* @var $renderer \Zf2datatable\Renderer\AbstractRenderer */
        $renderer = $this->getMockForAbstractClass('Zf2datatable\Renderer\AbstractRenderer');
        $renderer->setMvcEvent($mvcEvent);

        $col1 = clone $this->colMock;
        $col1->setUniqueId('myCol');
        $col1->setFilterDefaultValue('filterValue');
        $renderer->setColumns(array(
            $col1
        ));

        $filters = $renderer->getFiltersDefault();
        $this->assertCount(1, $filters);
        $this->assertInstanceOf('Zf2datatable\Filter', $filters[0]);

        // getFilters are the same like getFiltersDefault in this case
        $this->assertEquals($filters, $renderer->getFilters());

        // 2nd call from array cache
        $this->assertEquals($filters, $renderer->getFilters());
    }

    public function testGetFiltersNothingOnlyFromCustom()
    {
        /* @var $renderer \Zf2datatable\Renderer\AbstractRenderer */
        $renderer = $this->getMockForAbstractClass('Zf2datatable\Renderer\AbstractRenderer');

        $col1 = clone $this->colMock;
        $col1->setUniqueId('myCol');
        $col1->setFilterDefaultValue('filterValue');
        $renderer->setColumns(array(
            $col1
        ));
    }

    public function testCurrentPageNumber()
    {
        /* @var $renderer \Zf2datatable\Renderer\AbstractRenderer */
        $renderer = $this->getMockForAbstractClass('Zf2datatable\Renderer\AbstractRenderer');

        $this->assertEquals(1, $renderer->getCurrentPageNumber());

        $renderer->setCurrentPageNumber(25);
        $this->assertEquals(25, $renderer->getCurrentPageNumber());
    }

    public function testGetItemsPerPage()
    {
        /* @var $renderer \Zf2datatable\Renderer\AbstractRenderer */
        $renderer = $this->getMockForAbstractClass('Zf2datatable\Renderer\AbstractRenderer');

        $this->assertEquals(25, $renderer->getItemsPerPage());
        $this->assertEquals(100, $renderer->getItemsPerPage(100));

        // exports are unlimited
        /* @var $renderer \Zf2datatable\Renderer\AbstractRenderer */
        $renderer = new \Zf2datatable\Renderer\TCPDF\Renderer();
        $this->assertEquals(- 1, $renderer->getItemsPerPage());
    }
}
