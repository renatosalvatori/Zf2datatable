<?php
namespace Zf2datatable\DataSource;

use Zf2datatable\DataSource\ZendSelect;

use Zend\Paginator\Adapter\DbSelect as PaginatorAdapter;
use Zend\Db\Sql;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;

class ZendTableGateway extends ZendSelect
{
    protected $table = null;
    protected $tableGateway = null;
    protected $sourceObject = null;


    public function __construct($data=null)
    {
        /*if ($data instanceof \Zend\Db\TableGateway\AbstractTableGateway) {
            $this->tableGateway     = $data;
            $this->table            = $this->tableGateway->getTable();
            $this->select           = $data->getDefaultSql();
        }
        else {
            throw new \InvalidArgumentException('A instance of Zend\Db\SqlSelect is needed to use this dataSource!');
        }*/

    }

    /**
     *
     * @param \Zend\Db\TableGateway\AbstractTableGateway $data
     */
    public function init($data){
        if ($data instanceof \Zend\Db\TableGateway\AbstractTableGateway) {
            $this->tableGateway     = $data;
            $this->table            = $this->tableGateway->getTable();
            $this->select           = $data->getDefaultSql();
            $this->setSourceObject($data);
        }
        else {
            throw new \InvalidArgumentException('A instance of Zend\Db\SqlSelect is needed to use this dataSource!');
        }

        if($this->getServiceLocator()->has('service_cache') && $this->getServiceLocator()->get('cache_metadata')['mode']=='enabled'){
            $this->setCache($this->getServiceLocator()->get('service_cache'));
        }

        return $this;
    }


    public function query($query, $where){
        $method='get'.$query;
        $this->select = $this->tableGateway->$method($where);
        return $this->select;
    }

    /**
     * @param array $data
     * @param $where
     * @return mixed
     */
    public function update($data, $where){
        $dataEm = null;
        $shortCircuit = function ($r){
            if (is_array($r) || $r instanceof \ArrayObject) {
                return true;
            }
            return false;
        };

        $results = $this->getEventManager()->trigger('pre.'.__FUNCTION__,$this->tableGateway,$data,$shortCircuit);
        if ($results->stopped()) {
    	   $dataEm = $results->last();
    	}




       if($dataEm instanceof \ArrayObject){
            $datapreupdate = $dataEm->getArrayCopy();
       }
       elseif(is_array($dataEm)){
            $datapreupdate = $dataEm;
        }
       else{
            if($data instanceof \ArrayObject){
                $datapreupdate = $data->getArrayCopy();
            }
            else{
                $datapreupdate = data;
            }
        }


        $result =$this->tableGateway->update($datapreupdate, $where);
        $this->getEventManager()->trigger('post.'.__FUNCTION__,$this->tableGateway,array('data'=>$data,'where'=>$where));
        return $result;
    }

    public function insert($data){
        $dataEm = null;
        $shortCircuit = function ($r){
            if (is_array($r) || $r instanceof \ArrayObject) {
                return true;
            }
            return false;
        };

        $results= $this->getEventManager()->trigger('pre.'.__FUNCTION__,$this->tableGateway,$data,$shortCircuit);
        if ($results->stopped()) {
    	   $dataEm = $results->last();
    	}

        if($dataEm instanceof \ArrayObject)
            $datapreinsert = $dataEm->getArrayCopy();
        elseif(is_array($dataEm)){
            $datapreinsert = $dataEm;
        }
        else{
            if($data instanceof \ArrayObject){
                $datapreinsert = $data->getArrayCopy();
            }
            else{
                $datapreinsert = data;
            }
        }

        $this->getAdapter()->getAdapter()->getDriver()->getConnection()->beginTransaction();

        $result =$this->tableGateway->insert($datapreinsert);

        if($result){
            $lastInsertId = $this->getAdapter()->getAdapter()->getDriver()->getLastGeneratedValue();
    	    $this->getAdapter()->getAdapter()->getDriver()->getConnection()->commit();
            $this->getEventManager()->trigger('post.'.__FUNCTION__,$this->tableGateway,array("id"=>$lastInsertId, "data"=>$data));
            return $result;
        }


        $this->getAdapter()->getAdapter()->getDriver()->getConnection()->rollback();

        return false;
    }

    public function delete($where){
         $results = $this->getEventManager()->trigger('pre.'.__FUNCTION__,$this->tableGateway,$where);
         $this->getAdapter()->getAdapter()->getDriver()->getConnection()->beginTransaction();
         $result = $this->tableGateway->delete($where);
         if($result){
            $this->getAdapter()->getAdapter()->getDriver()->getConnection()->commit();
            $this->getEventManager()->trigger('post.'.__FUNCTION__,$this->tableGateway,$where);
            return $result;
         }

         $this->getAdapter()->getAdapter()->getDriver()->getConnection()->rollback();

         return false;
    }


    public function getSourceObject()
    {
        return $this->sourceObject;
    }


    public function setSourceObject($sourceObject)
    {
        $this->sourceObject = $sourceObject;
        return $this;
    }
}

?>