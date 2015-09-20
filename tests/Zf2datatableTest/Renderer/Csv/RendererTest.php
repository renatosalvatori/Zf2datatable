<?php
namespace Zf2datatableTest\Renderer\Csv;

use PHPUnit_Framework_TestCase;
use Zf2datatable\Renderer\Csv;

/**
 * @group Renderer
 * @covers Zf2datatable\Renderer\Csv\Renderer
 */
class RendererTest extends PHPUnit_Framework_TestCase
{

    public function testGetName()
    {
        $renderer = new Csv\Renderer();

        $this->assertEquals('csv', $renderer->getName());
    }

    public function testIsExport()
    {
        $renderer = new Csv\Renderer();

        $this->assertTrue($renderer->isExport());
    }

    public function testIsHtml()
    {
        $renderer = new Csv\Renderer();

        $this->assertFalse($renderer->isHtml());
    }
}
