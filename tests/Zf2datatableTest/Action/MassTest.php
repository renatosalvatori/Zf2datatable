<?php
namespace Zf2datatableTest\Action;

use PHPUnit_Framework_TestCase;
use Zf2datatable\Action\Mass;

/**
 * @group Datagrid
 * @covers Zf2datatable\Action\Mass
 */
class MassTest extends PHPUnit_Framework_TestCase
{
    
    public function setUp(){
        
    }
    
    public function tearDown(){
        
    }

    public function testConstruct()
    {
        $mass = new Mass();

        $this->assertEquals($mass->getTitle(), '');
        $this->assertEquals($mass->getLink(), '');
        $this->assertFalse($mass->getConfirm());

        $mass = new Mass('my Title', '/my/link', true);
        $this->assertEquals($mass->getTitle(), 'my Title');
        $this->assertEquals($mass->getLink(), '/my/link');
        $this->assertTrue($mass->getConfirm());
    }

    public function testTitle()
    {
        $mass = new Mass();

        $mass->setTitle('my cool title');
        $this->assertEquals('my cool title', $mass->getTitle());
    }

    public function testLink()
    {
        $mass = new Mass();

        $mass->setLink('/my/awesome/page');
        $this->assertEquals('/my/awesome/page', $mass->getLink());
    }

    public function testConfirm()
    {
        $mass = new Mass();

        $mass->setConfirm(true);
        $this->assertTrue($mass->getConfirm());

        $mass->setConfirm(false);
        $this->assertFalse($mass->getConfirm());
    }
}
