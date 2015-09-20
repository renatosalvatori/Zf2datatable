<?php
namespace Zf2datatable\Examples\Tablegateway;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Expression;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class PersonTableGateway extends AbstractTableGateway
{
    public function __construct($_adapter)
    {
        $this->table = 'person';
        $this->adapter = $_adapter;
    }
    
    
    public function getDefaultSql($where)
    {
        $sql= new \Zend\Db\Sql\Sql($this->getAdapter());
        $select = $sql->select();
        $select->from(array(
            'p' => 'person'
        ));
        $select->join(array(
            'g' => 'group'
        ), 'g.id = p.primaryGroupId', 'name', 'left');
        
        
        return $select;
    }
    
    
    public function __call($method, $arguments){
        $this->table->$method($arguments);
    }
    
    
    public function getPerson($where)
    {
        $sql= new \Zend\Db\Sql\Sql($this->getAdapter());
        $select = $sql->select();
        $select->from(array(
            'p' => 'person'
        ));
        $select->join(array(
            'g' => 'group'
        ), 'g.id = p.primaryGroupId', 'name', 'left');
    
        return $select;
    }
    
    
}

?>