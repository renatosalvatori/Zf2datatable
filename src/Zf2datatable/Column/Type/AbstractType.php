<?php
namespace Zf2datatable\Column\Type;

use Zf2datatable\Filter;

abstract class AbstractType implements  TypeInterface
{


    /**
     * the default filter operation
     *
     * @return string
     */
    public function getFilterDefaultOperation()
    {
        return Filter::LIKE;
    }

    /**
     * Convert the user value to a general value, which will be filtered
     *
     * @param  string $val
     * @return string
     */
    public function getFilterValue($val)
    {
        return $val;
    }

    /**
     * Convert the value from the source to the value, which the user will see
     *
     * @param  string $val
     * @return string
     */
    public function getUserValue($val)
    {
        return $val;
    }


}

?>