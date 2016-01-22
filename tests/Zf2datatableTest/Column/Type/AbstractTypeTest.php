<?php
namespace Zf2datatableTest\Column\Type;

use Zf2datatable\Filter;

class AbstractTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var \Zf2datatable\\Column\Type\AbstractType
     */
    private $type;
    
    public function setUp()
    {
        $this->type = $this->getMockForAbstractClass('Zf2datatable\Column\Type\AbstractType');
    }
    
    
    
    public function testgetFilterDefaultOperation()
    {
        $this->assertEquals(Filter::LIKE, $this->type->getFilterDefaultOperation());
    }
    
    
    
    
    public function testGetFilterValue()
    {
        $this->assertEquals('01.05.12', $this->type->getFilterValue('01.05.12'));
    }
    
    
    
    public function testGetUserValue()
    {
        $this->assertEquals('01.05.12', $this->type->getUserValue('01.05.12'));
    }
}
?>