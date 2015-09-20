<?php
namespace Zf2datatable\Column\Formatter;

use Zf2datatable\Column\AbstractColumn;

class Email extends AbstractFormatter
{

    protected $validRenderers = array(
        'jqGrid',
        'bootstrapTable'
    );

    public function getFormattedValue(AbstractColumn $column)
    {
        $row = $this->getRowData();

        return '<a href="mailto:' . $row[$column->getUniqueId()] . '">' . $row[$column->getUniqueId()] . '</a>';
    }
}
