<?php
namespace Zf2datatable\Column\Formatter;

use Zf2datatable\Column\AbstractColumn;

class File extends AbstractFormatter
{

    const paramsSeparator = '~';
    protected $validRenderers = array(
        'jqGrid',
        'bootstrapTable'
    );

    public function getFormattedValue(AbstractColumn $column)
    {
        $row = $this->getRowData();
        $value = $row[$column->getUniqueId()];

        if($value!=''){
            //estract
            $value = explode(self::paramsSeparator, $value);
        }



        if($this->getAttribute('href')!== null){
            $href=$this->getAttribute('href');
        }
        else
            $href=$value[0];


        if($this->getAttribute('path')!== null){
            $path=$this->getAttribute('path');
        }
        else
            $path='public';



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

        $linkFile = "<a href=\"/$path/{$value[0]}\"  $title  $target > {$value[1]} </a>";
        return $linkFile;
    }


}