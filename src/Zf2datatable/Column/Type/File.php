<?php
namespace Zf2datatable\Column\Type;

class File extends AbstractType
{

    protected $separator;

    /**
     *
     * @param string $separator
     */
    public function __construct($separator = '~')
    {
        $this->setSeparator($separator);
    }

    /*
     * Set separator of the string to be used to explode the array
     *
     * @param  string   $separator
     * @return PhpArray
     */
    public function setSeparator($separator)
    {
        $this->separator = $separator;

        return $this;
    }

    /*
     * Get the string separator
     *
     * @return string
     */
    public function getSeparator()
    {
        return $this->separator;
    }


    public function getTypeName()
    {
        return 'file';
    }


    public function getUserValue($value)
    {
      return $value;
    }







}

?>