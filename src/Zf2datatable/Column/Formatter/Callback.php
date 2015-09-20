<?php
namespace Zf2datatable\Column\Formatter;
use Zf2datatable\Column\AbstractColumn;

class Callback extends AbstractFormatter
{

    protected $validRenderers = array(
        'jqGrid',
        'bootstrapTable'
    );



    public function getFormattedValue(AbstractColumn $column)
    {
        $row = $this->getRowData();

        return call_user_func_array($this->getCallBack(), array($row,$column->getUniqueId() ));
        //return '<a href="mailto:' . $row[$column->getUniqueId()] . '">' . $row[$column->getUniqueId()] . '</a>';
    }

    public function getCallBack()
    {
        return $this->callBack;
    }

    public function setCallBack($callBack)
    {
        $this->callBack = $callBack;
        return $this;
    }


    public function __sleep(){
        $this->setCallBack(null);
    }


    public function __wakeup(){
        $this->callBack = $callBack;
        return $this;
    }

}

?>