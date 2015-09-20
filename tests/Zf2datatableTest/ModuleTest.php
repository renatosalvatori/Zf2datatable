<?php
namespace Zf2datatableTest;

use PHPUnit_Framework_TestCase;
use zf2datatable\Module;

/**
 * @covers ZfcDatagrid\Module
 */
class ModuleTest extends PHPUnit_Framework_TestCase
{

    public function testGetAutoloaderConfig()
    {
        $this->markTestSkipped('skip module');
        $module = new Module();

        $this->assertTrue(is_array($module->getAutoloaderConfig()));
        $this->assertCount(2, $module->getAutoloaderConfig());
        $this->assertArrayHasKey('Zend\Loader\StandardAutoloader', $module->getAutoloaderConfig());
        $this->assertArrayHasKey('Zend\Loader\ClassMapAutoloader', $module->getAutoloaderConfig());
    }

    public function testGetConfig()
    {
        $this->markTestSkipped('skip module');
        $module = new Module();

        $this->assertTrue(is_array($module->getConfig()));
        $this->assertCount(4, $module->getConfig());
        $this->assertArrayHasKey('ZfcDatagrid', $module->getConfig());
    }
}
