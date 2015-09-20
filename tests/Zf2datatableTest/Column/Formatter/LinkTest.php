<?php
namespace Zf2datatableTest\Column\Formatter;

use Zf2datatable\Column\Formatter;
use PHPUnit_Framework_TestCase;

/**
 * @group Formatter
 * @covers Zf2datatable\Column\Formatter\Link
 */
class LinkTest extends PHPUnit_Framework_TestCase
{

    public function testGetValidRendererNames()
    {
        $formatter = new Formatter\Link();

        $this->assertEquals(array(
            'jqGrid',
            'bootstrapTable'
        ), $formatter->getValidRendererNames());
    }




    public function testGetFormattedValue()
    {
        $col = $this->getMockForAbstractClass('Zf2datatable\Column\AbstractColumn');
        $col->setUniqueId('myCol');

        $formatter = new Formatter\Link();
        $formatter->setAttribute('href','http://example.com');
        $formatter->setAttribute('title','mytitle');
        $formatter->setAttribute('target','__blank');
        $formatter->setRowData(array(
            'myCol' => 'http://example.com'
        ));

        $this->assertEquals('<a href="http://example.com">http://example.com</a>', $formatter->getFormattedValue($col));
    }
}
