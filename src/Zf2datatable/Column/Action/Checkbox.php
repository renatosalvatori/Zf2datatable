<?php
namespace Zf2datatable\Column\Action;

/**
 *
 * @todo Checkbox for multi row actions...
 */
class Checkbox extends AbstractAction
{

    private $name;
    protected $field_selector;

    public function __construct($name = 'rowSelections',$filter="ID")
    {
        parent::__construct();

        $this->name = $name;
        $this->field_selector = $filter;
    }

    /**
     *
     * @return string
     */
    protected function getHtmlType()
    {
        return '';
    }

    /**
     * @see \Zf2datatable\Column\Action\AbstractAction::toHtml()
     */
    public function toHtml(array $row)
    {
        $this->removeAttribute('name');
        $this->removeAttribute('value');

        $key = $this->name."_".$row[$this->field_selector];

        return '<input type="checkbox"  id="'.$key.'" name="' . $key . '" value="' . $row['idConcated'] . '" ' . $this->getAttributesString($row) . ' />';
    }
}
