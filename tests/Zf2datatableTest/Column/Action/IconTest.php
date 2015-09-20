<?php
namespace Zf2datatableTest\Column\Action;

use PHPUnit_Framework_TestCase;
use Zf2datatable\Column\Action\Icon;

/**
 * @group Column
 * @covers Zf2datatable\Column\Action\Icon
 */
class IconTest extends PHPUnit_Framework_TestCase
{

    public function testConstruct()
    {
        $icon = new Icon();

        $this->assertEquals(array(
            'href' => '#'
        ), $icon->getAttributes());
    }

    public function testIconClass()
    {
        $icon = new Icon();

        $this->assertFalse($icon->hasIconClass());

        $icon->setIconClass('icon-add');
        $this->assertEquals('icon-add', $icon->getIconClass());
        $this->assertTrue($icon->hasIconClass());

        $this->assertEquals('<a href="#"><i class="icon-add"></i></a>', $icon->toHtml(array()));
    }

    public function testIconLink()
    {
        $icon = new Icon();

        $this->assertFalse($icon->hasIconLink());

        $icon->setIconLink('/images/21/add.png');
        $this->assertEquals('/images/21/add.png', $icon->getIconLink());
        $this->assertTrue($icon->hasIconLink());

        $this->assertEquals('<a href="#"><img src="/images/21/add.png" /></a>', $icon->toHtml(array()));
    }


    /**
     * @expectedException  InvalidArgumentException
     */
    public function testException()
    {
        $icon = new Icon();

        $this->setExpectedException('InvalidArgumentException');

        $icon->toHtml(array());
    }
}
