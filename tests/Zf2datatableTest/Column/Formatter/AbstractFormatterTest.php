<?php
namespace Zf2datatableTest\Column\Formatter;


/**
 @group Formatter
*/
class AbstractFormatterTest extends \PHPUnit_Framework_TestCase
{
    public function testRowData()
    {
        $formatter = $this->getMockForAbstractClass('Zf2datatable\Column\Formatter\AbstractFormatter');
        $this->assertEquals(array(), $formatter->getRowData());

        $data = array(
            'myCol' => 123,
            'myCol2' => 'text'
        );

        $formatter->setRowData($data);
        $this->assertEquals($data, $formatter->getRowData());
    }

    public function testRendererName()
    {
        $formatter = $this->getMockForAbstractClass('Zf2datatable\Column\Formatter\AbstractFormatter');

        $this->assertNull($formatter->getRendererName());

        $formatter->setRendererName('jqGrid');
        $this->assertEquals('jqGrid', $formatter->getRendererName());
    }

    public function testIsApply()
    {
        $formatter = $this->getMockForAbstractClass('Zf2datatable\Column\Formatter\AbstractFormatter');
        $formatter->setValidRendererNames(array(
            'jqGrid'
        ));

        $formatter->setRendererName('jqGrid');
        $this->assertTrue($formatter->isApply());

        $formatter->setRendererName('tcpdf');
        $this->assertFalse($formatter->isApply());
    }


    public function testFormat()
    {
        $formatter = $this->getMockForAbstractClass('Zf2datatable\Column\Formatter\AbstractFormatter');
        $formatter->setValidRendererNames(array(
            'jqGrid'
        ));
        $data = array(
            'myCol' => 123,
            'myCol2' => 'text'
        );
        $formatter->setRowData($data);

        $col = $this->getMockForAbstractClass('Zf2datatable\Column\AbstractColumn');
        $col->setUniqueId('myCol');

        $formatter->setRendererName('tcpdf');
        $this->assertEquals(123, $formatter->format($col));

        //Null because the method is not implemented in AbstractClass!
        $formatter->setRendererName('jqGrid');
        $this->assertEquals(null, $formatter->format($col));
    }


    public function testGetOptionsValue(){
        $formatter = $this->getMockForAbstractClass('Zf2datatable\Column\Formatter\AbstractFormatter');

        $this->assertEquals(array(), $formatter->getOptions());
        $this->assertEquals(0, count($formatter->getOptions()));

        $formatter->setOptions(array('test'=>'test'));

        $this->assertEquals(array('test'=>'test'), $formatter->getOptions());
        $this->assertEquals(1, count($formatter->getOptions()));

        $formatter->setOption('test','test');
        $this->assertEquals('test', $formatter->getOption('test'));

    }





    public function testGetAttributesValue(){
        $formatter = $this->getMockForAbstractClass('Zf2datatable\Column\Formatter\AbstractFormatter');

        $this->assertEquals(array(), $formatter->getAttributes());
        $this->assertEquals(0, count($formatter->getAttributes()));

        $formatter->setAttributes(array('test'=>'test'));

        $this->assertEquals(array('test'=>'test'), $formatter->getAttributes());
        $this->assertEquals(1, count($formatter->getAttributes()));


        $formatter->setAttribute('test','test');
        $this->assertEquals('test', $formatter->getAttribute('test'));


    }


    public function testValidRender(){

        $formatter = $this->getMockForAbstractClass('Zf2datatable\Column\Formatter\AbstractFormatter');
        $formatter->setRendererName('testRender');
        $this->assertEquals('testRender', $formatter->getRendererName());

        $formatter->setValidRendererNames(['testRender']);
        $this->assertEquals(['testRender'], $formatter->getValidRendererNames());

    }






}

?>