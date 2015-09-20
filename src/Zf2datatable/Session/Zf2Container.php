<?php
namespace Zf2datatable\Session;

use Zend\Session\Container;

class Zf2Container extends Container
{

    public function getItem($key) {
        return parent::offsetGet($key);
    }

    public function setItem($key ,$value) {
       parent::offsetSet($key);
        return $this;
    }


    public function setTag($key ,$value) {
        parent::offsetSet($key);
        return $this;
    }

}

?>