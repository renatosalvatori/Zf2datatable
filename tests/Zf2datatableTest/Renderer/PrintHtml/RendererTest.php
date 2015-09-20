<?php
namespace Zf2datatableTest\Renderer\PrintHtml;

use PHPUnit_Framework_TestCase;
use Zf2datatable\Renderer\PrintHtml;

/**
 * @group Renderer
 * @covers Zf2datatable\Renderer\PrintHtml\Renderer
 */
class RendererTest extends PHPUnit_Framework_TestCase
{

    public function testGetName()
    {
        $renderer = new PrintHtml\Renderer();

        $this->assertEquals('printHtml', $renderer->getName());
    }

    public function testIsExport()
    {
        $renderer = new PrintHtml\Renderer();

        $this->assertTrue($renderer->isExport());
    }

    public function testIsHtml()
    {
        $renderer = new PrintHtml\Renderer();

        $this->assertTrue($renderer->isHtml());
    }
}
