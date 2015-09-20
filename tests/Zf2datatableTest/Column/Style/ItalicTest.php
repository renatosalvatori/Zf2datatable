<?php
namespace Zf2datatableTest\Column\Style;

use PHPUnit_Framework_TestCase;
use Zf2datatable\Column\Style;

/**
 * @group Column
 * @covers Zf2datatable\Column\Style\Italic
 */
class ItalicTest extends PHPUnit_Framework_TestCase
{

    public function testCanCreateInstance()
    {
        $bold = new Style\Italic();
    }
}
