<?php
namespace Zf2datatable\DataSource;

use Zf2datatable\Column;
use Zend\Paginator\Adapter\DbSelect as PaginatorAdapter;
use Zend\Db\Sql;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Where;


class ZendSelect extends AbstractDataSource
{

    const FOREIGN_KEY = 'FOREIGN KEY';
    const PRIMARY_KEY = 'PRIMARY KEY';


    /**
     *
     * @var Sql\Select
     */
    protected  $select;


    /**
     *
     * @var Sql\Select
     */
    protected  $selectDetail;



    /**
     *
     * @var \Zend\Db\Sql\Sql
     */
    protected  $sqlObject;


    /**
     *
     */
    protected  $foreign_key;


	/**
     * Data source
     *
     * @param mixed $data
     */
    public function __construct($data=null)
    {
        /*if ($data instanceof Sql\Select) {
            $this->select = $data;
        } else {
            throw new \InvalidArgumentException('A instance of Zend\Db\SqlSelect is needed to use this dataSource!');
        }*/
    }


    public function init($data){
        if ($data instanceof Sql\Select) {
            $this->select = $data;
        } else {
            throw new \InvalidArgumentException('A instance of Zend\Db\SqlSelect is needed to use this dataSource!');
        }

        if($this->getServiceLocator()->has('service_cache') && $this->getServiceLocator()->get('cache_metadata')['mode']=='enabled'){
            $this->setCache($this->getServiceLocator()->get('service_cache'));
        }

        return $this;
    }


    /**
     *
     * @return Sql\Select
     */
    public function getData()
    {
        return $this->select;
    }


    /**
     *
     * @return Sql\Select
     */
    public function getDataDetail()
    {
    	if($this->selectDetail == null){
            $this->selectDetail = $this->select;
    	}
    	return $this->selectDetail;
    }


    /**
     * @param \Zend\Db\Sql\Select $selectDetail
     */
    public function setSelectDetail($selectDetail) {
    	$this->selectDetail = $selectDetail;
    }


    public function setAdapter($adapterOrSqlObject)
    {
        if ($adapterOrSqlObject instanceof \Zend\Db\Sql\Sql) {
            $this->sqlObject = $adapterOrSqlObject;
        } elseif ($adapterOrSqlObject instanceof \Zend\Db\Adapter\Adapter) {
            $this->sqlObject = new \Zend\Db\Sql\Sql($adapterOrSqlObject);
        } else {
            throw new \InvalidArgumentException('Object of "Zend\Db\Sql\Sql" or "Zend\Db\Adapter\Adapter" needed.');
        }
    }

    /**
     *
     * @return \Zend\Db\Sql\Sql
     */
    public function getAdapter()
    {
        return $this->sqlObject;
    }

    public function execute()
    {
        if ($this->getAdapter() === null || ! $this->getAdapter() instanceof \Zend\Db\Sql\Sql) {
            throw new \Exception('Object "Zend\Db\Sql\Sql" is missing, please call setAdapter() first!');
        }

        $platform = $this->getAdapter()
            ->getAdapter()
            ->getPlatform();

        $select = $this->getData();


        /*
         * Step 1) Apply needed columns
         */
        $selectColumns = array();
        foreach ($this->getColumns() as $column) {
            if ($column instanceof Column\Select) {
                $colString = $column->getSelectPart1();
                if ($column->getSelectPart2() != '') {
                    $colString = new Expression($platform->quoteIdentifier($colString) . $platform->getIdentifierSeparator() . $platform->quoteIdentifier($column->getSelectPart2()));
                }

                $selectColumns[$column->getUniqueId()] = $colString;
            }
        }


        $select->columns($selectColumns, false);

        $joins = $select->getRawState('joins');
        $select->reset('joins');
        foreach ($joins as $join) {
            $select->join($join['name'], $join['on'], array(), $join['type']);
        }


        /*
         * Step 2) Apply sorting
         */
        if (count($this->getSortConditions()) > 0) {
            // Minimum one sort condition given -> so reset the default orderBy
            $select->reset(Sql\Select::ORDER);

            foreach ($this->getSortConditions() as $sortCondition) {
                $column = $sortCondition['column'];

                //$select->order($column->getUniqueId() . ' ' . $sortCondition['sortDirection']);
                $select->order($column->getSelectPart1(). $platform->getIdentifierSeparator().$column->getSelectPart2() . ' ' . $sortCondition['sortDirection']);
            }
        }



        /*
         * Step 3) Apply filters
         */
        $filterColumn = new ZendSelect\Filter($this->getAdapter(), $select);


        foreach ($this->getFilters() as $filter) {

            /* @var $filter \ZfcDatagrid\Filter */
            if ($filter->isColumnFilter() === true) {
                $filterColumn->applyFilter($filter);
            }
        }

        //var_dump($this->getFilters());


        /*
         * Step 4) Pagination
         *
         *
         */
        /*
        echo "---->".$this->getAdapter()->getSqlStringForSqlObject($select);
        die('sssss');



        echo "---->".$this->getAdapter()->getSqlStringForSqlObject($select)."<br />";
        die('xxxx');

*/
         //echo "---->".$this->getAdapter()->getSqlStringForSqlObject($select);

        $this->setPaginatorAdapter(new PaginatorAdapter($select, $this->getAdapter()));
    }

    /**
     *
     * @throws \Exception
     */
    public function executeDetail($filterValue=array())
    {
    	if ($this->getAdapter() === null || ! $this->getAdapter() instanceof \Zend\Db\Sql\Sql) {
    		throw new \Exception('Object "Zend\Db\Sql\Sql" is missing, please call setAdapter() first!');
    	}

    	$alias='';
    	$platform = $this->getAdapter()
    	->getAdapter()
    	->getPlatform();

    	$select = $this->getDataDetail();
    	$where = new \Zend\Db\Sql\Where();

    	$mainTable = $select->getRawState('table');
    	if(is_array($mainTable)){
    		$alias=key($mainTable).".";
    	}

    	//$filterValue = [];
    	$oConstraints = $this->getMetaDataInfo()[1]["constraints"];
    	$constraints= $oConstraints[0]->getColumns();

    	//var_dump($filterValue);
    	if(is_array($filterValue)){
        	foreach($constraints as $constraint ){
        		if(array_key_exists($constraint,$filterValue))
        			$where->equalTo($alias.$constraint, $filterValue[$constraint]);
        	}
    	}
    	$statement = $this->getAdapter()->prepareStatementForSqlObject($select->where($where));
    	//echo $this->getAdapter()->getSqlStringForSqlObject($select->where($where));

    	return $result = $statement->execute()->current();
    }

    public function executeExternalQuery($source=array(),$where)
    {
        $source =array('table'=>'group','id'=>'id','value'=>'name');
        $select = $this->getData();
        $sqlclone = clone $select;

        $sqlclone->reset(Sql\Select::COLUMNS);
        $sqlclone->reset(Sql\Select::JOINS);
        $sqlclone->reset(Sql\Select::ORDER);

        $sqlclone->from($source['table'])->columns(array($source['id'],$source['value']));
        if(is_array($where) || $where instanceof \Zend\Db\Sql\Where){
            $sqlclone->where($where);
        }

        $statement = $this->getAdapter()->prepareStatementForSqlObject($sqlclone);
        //echo $this->getAdapter()->getSqlStringForSqlObject($sqlclone);

        $results = $statement->execute();
        $source_value = array();
        foreach ($results as $result){
            $source_value[$result['id']]=$result['name'];
        }

        return $source_value;

    }

    /** to populate **/
    public function findByIdentity($where){
    	$sql = $this->getAdapter();
    	$platform = $this->getAdapter()
    	->getAdapter()
    	->getPlatform();


    	$select = $sql->select();


        $sqlclone = clone $select;

        $sqlclone->reset(Sql\Select::COLUMNS);
        $sqlclone->reset(Sql\Select::JOINS);
        $sqlclone->reset(Sql\Select::ORDER);
        //$sqlclone->reset(Sql\Select::FROM);

    	$filterColumn = new ZendSelect\Filter($this->getAdapter(), $sqlclone);
    	foreach ($this->getFilters() as $filter) {

    	    /* @var $filter \ZfcDatagrid\Filter */
    	    if ($filter->isColumnFilter() === true) {
    	        //$filterColumn->applyFilter($filter);
    	    }
    	}
    	$table = $this->table;
    	if($this->alias_table){
    	    $table = array($this->alias_table=> $this->table);
    	}


    	$sqlclone->columns(array(Sql\Select::SQL_STAR))->from($table)->where($where);
        //echo "*****".$sql->getSqlStringForSqlObject($sqlclone);
        //die();

    	$statement = $sql->prepareStatementForSqlObject($sqlclone);
    	$result = $statement->execute()->current ();


    	if(!is_array( $result ) && !$result instanceof \Traversable){
    	    return null;
    	}

    	return new \ArrayObject($result);
    }


    public function getDefaultBindObject(){
        return new \ArrayObject();
    }



    /**
     * @param array $data
     */
    public function update($data, $where){
        $sql = $this->getAdapter();
        $sql->setTable($this->table);
        $update = $sql->update();


        $shortCircuit = function ($r){
            if (is_array($r) || $r instanceof \ArrayObject) {
                return true;
            }
            return false;
        };


        $results= $this->getEventManager()->trigger('pre.'.__FUNCTION__,$sql,$data,$shortCircuit);
        if ($results->stopped()) {
    	   $dataEm = $results->last();
    	}

        if($dataEm instanceof \ArrayObject) {
            $datapreupdate = $dataEm->getArrayCopy();

        } elseif(is_array($dataEm)) {
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

    	$sql = $this->getAdapter();
    	$update = $sql->update();
    	$update->table($this->table);
       	$update->set($datapreupdate);
    	$update->where($where);
    	$statement = $sql->prepareStatementForSqlObject($update);
    	//echo $sql->getSqlStringForSqlObject($update);

    	$this->getAdapter()->getAdapter()->getDriver()->getConnection()->beginTransaction();

    	$result = $statement->execute();
    	if($result){
    	     $this->getAdapter()->getAdapter()->getDriver()->getConnection()->commit();
    	     $this->getEventManager()->trigger('post.'.__FUNCTION__,$sql,$data);
    	     return $result;
    	 }

    	 $this->getAdapter()->getAdapter()->getDriver()->getConnection()->rollback();

    	 return false;
    }

    /**
     *
     * @param array $data
     * @param array || object $where
     */
    public function insert($data){

        $sql = $this->getAdapter();
        $sql->setTable($this->table);
        $insert = $sql->insert();


        $shortCircuit = function ($r){
            if (is_array($r) || $r instanceof \ArrayObject) {
                return true;
            }
            return false;
        };


    	$results= $this->getEventManager()->trigger('pre.'.__FUNCTION__,$sql,$data,$shortCircuit);

    	if ($results->stopped()) {
    	   $dataEm = $results->last();
    	}

        if($dataEm instanceof \ArrayObject) {
            $datapreinsert = $dataEm->getArrayCopy();

        } elseif(is_array($dataEm)) {
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



    	$insert->into($this->table);
    	$insert->values($datapreinsert);
    	$statement = $sql->prepareStatementForSqlObject($insert);


        //echo $sql->getSqlStringForSqlObject($insert);
    	$this->getAdapter()->getAdapter()->getDriver()->getConnection()->beginTransaction();

    	$result = $statement->execute();


    	if($result){
    	    $lastInsertId = $this->getAdapter()->getAdapter()->getDriver()->getLastGeneratedValue();
    	    $this->getAdapter()->getAdapter()->getDriver()->getConnection()->commit();
    	    $this->getEventManager()->trigger('post.'.__FUNCTION__,$sql,array("id"=>$lastInsertId,"data"=>$data));
    	    return $result;
    	}

    	$this->getAdapter()->getAdapter()->getDriver()->getConnection()->rollback();
    	return false;
    }

    /**
     *
     * @param array || object $where
     */
    public function delete($where){
    	//die('delete');
   	$this->getEventManager()->trigger('pre'.__FUNCTION__,null,array());
    	$sql = $this->getAdapter();
    	$delete = $sql->delete();
    	$delete->from($this->table)->where($where);
    	$statement = $sql->prepareStatementForSqlObject($delete);
    	$this->getEventManager()->trigger('post'.__FUNCTION__,null,array());

    	$this->getAdapter()->getAdapter()->getDriver()->getConnection()->beginTransaction();
    	$result = $statement->execute();
    	if($result){
    	    $this->getAdapter()->getAdapter()->getDriver()->getConnection()->commit();
    	    $this->getEventManager()->trigger('post.'.__FUNCTION__,$this->tableGateway,$where);
    	    return $result;
    	}
    	$this->getAdapter()->getAdapter()->getDriver()->getConnection()->rollback();

         return false;
    }

    /**
     *
     * @return array  multitype:multitype:
     */
    public function getMetaDataInfo(){
       if($this->getServiceLocator()->has('service_cache') && $this->getServiceLocator()->get('cache_metadata')['mode']=='enabled'){
            $result = $this->getCache()->getItem($this->table);

            if (!empty($result)) {

                return $result;
            }
        }


    	$metaData 	= new \Zend\Db\Metadata\Metadata($this->getAdapter()->getAdapter());
    	$table  	= $metaData->getTable($this->table);
    	$columns    = array();
    	$contraints = array();


    	foreach ($table->getColumns() as $key => $col){
    	    $columns[$col->getName ()]= array('name'=>$col->getName (), 'datatype'=>strtoupper($col->getDataType ()));
    	}



    	//var_dump($columns);
    	$contraints = $table->getConstraints();
    	$result = [["columns"=>$columns],
    			["constraints"=>$contraints]];



    	if($this->getServiceLocator()->has('service_cache') && $this->getServiceLocator()->get('cache_metadata')['mode']=='enabled'){
    	    $this->getCache()->setItem($this->table,$result);
    	    # Tag the key when saving
    	    if(method_exists($this->getCache(), 'setTags')){
    	       $this->getCache()->setTags($this->table, array($this->table));
    	    }
    	}



    	return $result;
    }


    public function getForeignKey(){

        if($this->getServiceLocator()->has('service_cache') && $this->getServiceLocator()->get('cache_metadata')['mode']=='enabled'){
            $this->foreign_key = $this->getCache()->getItem($this->table.'_ForeignKey');
            if (!empty($this->foreign_key) || $this->getCache()->hasItem($this->table.'_ForeignKey')) {
                return $this->foreign_key;
            }
        }

        $metaData 	= new \Zend\Db\Metadata\Metadata($this->getAdapter()->getAdapter());
        $table  	= $metaData->getTable($this->table);
        $contraints = $table->getConstraints();
        foreach($contraints as $contraint){

            if ($contraint->getType()== self::FOREIGN_KEY){
                $this->foreign_key[implode("_",$contraint->getColumns())] = array(
                    "column"=>$contraint->getColumns(),
                    "tableref"=>$contraint->getReferencedTableName(),
                    "columnref"=>$contraint->getReferencedColumns());
            }
        }

        if($this->getServiceLocator()->has('service_cache') && $this->getServiceLocator()->get('cache_metadata')['mode']=='enabled'){
            $this->getCache()->setItem($this->table.'_ForeignKey',$this->foreign_key);
            # Tag the key when saving
            if(method_exists($this->getCache(), 'setTags')){
                 $this->getCache()->setTags($this->table.'_ForeignKey', array($this->table));
            }

        }


        return $this->foreign_key;
    }



}
