<?php
namespace Zf2datatableTest\Column\Style;

use PHPUnit_Framework_TestCase;
use Zf2datatable\Column\Style;

/**
 * @group Column
 * @covers Zf2datatable\Column\Style\Bold
 */
class BoldTest extends PHPUnit_Framework_TestCase
{

    public function testCanCreateInstance()
    {
        $bold = new Style\Bold();
    }
}
