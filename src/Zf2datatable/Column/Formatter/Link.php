<?php
namespace Zf2datatable\Column\Formatter;

use Zf2datatable\Column\AbstractColumn;

class Link extends AbstractFormatter
{

    protected $validRenderers = array(
        'jqGrid',
        'bootstrapTable'
    );

    public function getFormattedValue(AbstractColumn $column)
    {
        $row = $this->getRowData();
        $value = $row[$column->getUniqueId()];


        if($this->getAttribute('href')!== null){
            $href=$this->getAttribute('href').$value;
        }
        else
            $href=$value;


        if($this->getAttribute('title')!== null){
            $title="title={$this->getAttribute('title')}";
        }
        else
            $title='';


        if($this->getAttribute('target')!== null){
            $target="target={$this->getAttribute('target')}";
        }
        else
            $target='';

        return '<a href="' . $href . '"  '.$title.'  '.$target.' >' . $value . '</a>';
    }
}
