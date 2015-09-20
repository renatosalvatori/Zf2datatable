<?php
namespace Zf2datatable\Column\DataPopulation;

use Zf2datatable\Column;

/**
 * Get the data from an external object
 */
class Object implements DataPopulationInterface
{

    /**
     *
     * @var ObjectAwareInterface
     */
    private $object;

    /**
     *
     * @var array
     */
    private $objectParameters = array();

    /**
     *
     * @param  ObjectAwareInterface $object
     * @throws \Exception
     */
    public function setObject(ObjectAwareInterface $object)
    {
        $this->object = $object;
    }

    /**
     *
     * @return ObjectAwareInterface
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Apply a dynamic parameter based on row/column value
     *
     * @param string         $objectParameterName
     * @param AbstractColumn $column
     */
    public function addObjectParameterColumn($objectParameterName, Column\AbstractColumn $column)
    {
        $this->objectParameters[] = array(
            'objectParameterName' => $objectParameterName,
            'column' => $column
        );
    }

    /**
     *
     * @return array
     */
    public function getObjectParametersColumn()
    {
        return $this->objectParameters;
    }

    /**
     * Directly apply a "static" parameter
     *
     * @param string $name
     * @param mixed  $value
     */
    public function setObjectParameter($name, $value)
    {
        $this->getObject()->setParameterFromColumn($name, $value);
    }

    /**
     *
     * @return string
     */
    public function toString()
    {
        return $this->getObject()->toString();
    }
}
