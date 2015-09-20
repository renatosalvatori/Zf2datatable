<?php
namespace Zf2datatableTest\Renderer\PHPExcel;

use PHPUnit_Framework_TestCase;
use Zf2datatable\Renderer\PHPExcel;

/**
 * @group Renderer
 * @covers Zf2datatable\Renderer\PHPExcel\Renderer
 */
class RendererTest extends PHPUnit_Framework_TestCase
{

    public function testGetName()
    {
        $renderer = new PHPExcel\Renderer();

        $this->assertEquals('PHPExcel', $renderer->getName());
    }

    public function testIsExport()
    {
        $renderer = new PHPExcel\Renderer();

        $this->assertTrue($renderer->isExport());
    }

    public function testIsHtml()
    {
        $renderer = new PHPExcel\Renderer();

        $this->assertFalse($renderer->isHtml());
    }
}
