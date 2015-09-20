<?php
namespace Zf2datatable\Column\Type\Adapter;

class StaticAdapterType
{
    protected static $adapter;

    public static function setAdapter($key,$adapter){
        if(!isset(self::$adapter[$key])){
            self::$adapter[$key] = $adapter;
        }
    }

    public static function getAdapter($key){
        return self::$adapter[$key];
    }
}

?>