<?php
namespace Zf2datatableTest\Column\Formatter;

use Zf2datatable\Column\Formatter;
use PHPUnit_Framework_TestCase;

/**
 * @group Column
 * @covers Zf2datatable\Column\Formatter\Image
 */
class ImageTest extends PHPUnit_Framework_TestCase
{

    public function testGetValidRendererNames()
    {
        $formatter = new Formatter\Image();

        $this->assertEquals(array(
            'jqGrid',
            'bootstrapTable',
            'printHtml'
        ), $formatter->getValidRendererNames());
    }
}
