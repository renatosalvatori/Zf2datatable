<?php
namespace Zf2datatableTest\Renderer\TCPDF;

use PHPUnit_Framework_TestCase;
use Zf2datatable\Renderer\TCPDF;

/**
 * @group Renderer
 * @covers Zf2datatable\Renderer\TCPDF\Renderer
 */
class RendererTest extends PHPUnit_Framework_TestCase
{

    public function testGetName()
    {
        $renderer = new TCPDF\Renderer();

        $this->assertEquals('TCPDF', $renderer->getName());
    }

    public function testIsExport()
    {
        $renderer = new TCPDF\Renderer();

        $this->assertTrue($renderer->isExport());
    }

    public function testIsHtml()
    {
        $renderer = new TCPDF\Renderer();

        $this->assertFalse($renderer->isHtml());
    }
}
