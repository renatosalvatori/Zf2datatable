<?php
namespace Zf2datatable\Column\Formatter;

use Zf2datatable\Column\AbstractColumn;

abstract class AbstractFormatter
{

    private $data = array();

    /**
     * var @options array
     */
    protected $options = array();

    protected $attributes = array();

    private   $rendererName;

    protected $validRenderers = array();


    public function getAttributes() {
        return $this->attributes;
    }

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    public function getAttribute($key) {
        return $this->attributes[$key];
    }

    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
        return $this;
    }


    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    public function getOption($key)
    {
        return $this->options[$key];
    }

    /**
     *
     * @param string $key
     * @param string / number/ array $value
     * @return \Zf2datatable\Column\Formatter\AbstractFormatter
     */
    public function setOption($key,$value)
    {
        $this->options[$key] = $value;
        return $this;
    }


    public function setRowData(array $data)
    {
        $this->data = $data;
    }

    /**
     *
     * @return array
     */
    public function getRowData()
    {
        return $this->data;
    }

    /**
     *
     * @param string $name
     */
    public function setRendererName($name = null)
    {
        $this->rendererName = $name;
    }

    /**
     *
     * @return string null
     */
    public function getRendererName()
    {
        return $this->rendererName;
    }

    /**
     *
     * @param array $validRendrerers
     */
    public function setValidRendererNames(array $validRendrerers)
    {
        $this->validRenderers = $validRendrerers;
    }

    /**
     *
     * @return array
     */
    public function getValidRendererNames()
    {
        return $this->validRenderers;
    }

    /**
     *
     * @return boolean
     */
    public function isApply()
    {
        if (in_array($this->getRendererName(), $this->validRenderers)) {
            return true;
        }

        return false;
    }

    /**
     *
     * @param  AbstractColumn $column
     * @return string
     */
    public function format(AbstractColumn $column)
    {
        $data = $this->getRowData();
        if ($this->isApply() === true) {
            return $this->getFormattedValue($column);
        }

        return $data[$column->getUniqueId()];
    }

    /**
     *
     * @param AbstractColumn $columnUniqueId
     *
     * @return string
     */
    abstract public function getFormattedValue(AbstractColumn $column);






}
