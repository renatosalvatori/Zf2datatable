<?php
namespace Zf2datatableTest\Column\Formatter;

use Zf2datatable\Column\Formatter;
use PHPUnit_Framework_TestCase;

/**
 * @group Column
 * @covers Zf2datatable\Column\Formatter\Email
 */
class EmailTest extends PHPUnit_Framework_TestCase
{

    public function testGetValidRendererNames()
    {
        $formatter = new Formatter\Email();

        $this->assertEquals(array(
            'jqGrid',
            'bootstrapTable'
        ), $formatter->getValidRendererNames());
    }

    public function testGetFormattedValue()
    {
        $col = $this->getMockForAbstractClass('Zf2datatable\Column\AbstractColumn');
        $col->setUniqueId('myCol');

        $formatter = new Formatter\Email();
        $formatter->setRowData(array(
            'myCol' => 'name@example.com'
        ));

        $this->assertEquals('<a href="mailto:name@example.com">name@example.com</a>', $formatter->getFormattedValue($col));
    }
}
