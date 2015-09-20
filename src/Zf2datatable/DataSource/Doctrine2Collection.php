<?php
namespace Zf2datatable\DataSource;

use Zf2datatable\DataSource\PhpArray as SourceArray;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\Collection;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Doctrine\ORM\QueryBuilder;

class Doctrine2Collection extends AbstractDataSource
{

    /**
     *
     * @var Collection
     */
    private $data;

    /**
     *
     * @var EntityManager
     */
    private $em;

    /**
     * Data source
     *
     * @param mixed $data
     */
    public function __construct($data)
    {
        if ($data instanceof Collection) {
            $this->data = $data;
        } else {
            $return = $data;
            if (is_object($data)) {
                $return = 'instanceof ' . get_class($return);
            }
            throw new \InvalidArgumentException('Unknown data input: "' . $return . '"');
        }
    }

    /**
     *
     * @return Collection
     */
    public function getData()
    {
        return $this->data;
    }


    /**
     *
     * @return Collection
     */
    public function getDataDetail()
    {
        return $this->data;
    }



    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    public function execute()
    {

        $hydrator = new DoctrineHydrator($this->getEntityManager());

        $dataPrepared = array();
        foreach ($this->getData() as $row) {
            $dataExtracted = $hydrator->extract($row);

            $rowExtracted = array();
            foreach ($this->getColumns() as $column) {
                /* @var $column \ZfcDatagrid\Column\AbstractColumn */
                $part1 = $column->getSelectPart1();
                $part2 = $column->getSelectPart2();

                if ($part2 === null) {
                    if (isset($dataExtracted[$part1])) {
                        $rowExtracted[$column->getUniqueId()] = $dataExtracted[$part1];
                    }
                } else {
                    // NESTED
                    if (isset($dataExtracted[$part1])) {
                        $dataExtractedNested = $hydrator->extract($dataExtracted[$part1]);
                        if (isset($dataExtractedNested[$part2])) {
                            $rowExtracted[$column->getUniqueId()] = $dataExtractedNested[$part2];
                        }
                    }
                }
            }

            $dataPrepared[] = $rowExtracted;
        }

        $source = new SourceArray($dataPrepared);
        $source->setColumns($this->getColumns());
        $source->setSortConditions($this->getSortConditions());
        $source->setFilters($this->getFilters());
        $source->execute();

        $this->setPaginatorAdapter($source->getPaginatorAdapter());
    }


    public function executeDetail($filterValue=array())
    {

      $hydrator = new DoctrineHydrator($this->getEntityManager());
      $qb = $this->getData();

       if($qb instanceof \Doctrine\ORM\QueryBuilder){
           $alias = current($qb->getRootAliases());
           while(list($chiave,$valore) = each($filterValue)){
                $qb->andWhere( $alias.'.'.$chiave.' = '.$valore);
           }
           $row = $this->getData()->getQuery()->getResult();


           if(is_array($row[0])){
              $dataExtracted = $row[0];
           }
           else
              $dataExtracted = $hydrator->extract($row[0]);
       }
       return $dataExtracted;
    }

    /** external query **/
    public function executeExternalQuery($source=array(),$where)
    {
        $source =array('table'=>'group','id'=>'id','value'=>'name');
    }

    /** to populate **/
    public function findByIdentity($where){
        $entiyObject = $this->getEntityManager()->find($this->getEntity(), $where);
        /*if(null===$entiyObject){
            $entityCls   = $this->getEntity();
            $entiyObject = new $entityCls();
        }*/

        return $entiyObject;
    }

    /** default bind object **/
    public function getDefaultBindObject(){
        $entityCls   = $this->getEntity();
        return new $entityCls();;
    }

    /**   update **/
    public function update($entity,$where=null){
        $results= $this->getEventManager()->trigger('pre'.__FUNCTION__,$entity,$where);
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        $results= $this->getEventManager()->trigger('post'.__FUNCTION__,$entity,$where);
        return true;
    }

    /**   insert **/
    public function insert($entity){

        $results= $this->getEventManager()->trigger('pre'.__FUNCTION__,$entity,array());
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        $results= $this->getEventManager()->trigger('post'.__FUNCTION__,$entity,array());
        return true;
    }

    /**   delete **/
    public function delete($where){
        $results= $this->getEventManager()->trigger('pre'.__FUNCTION__,$entity,$where);
        $entity = $this->getEntityManager()->find($this->getEntity(), $where);
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
        $results= $this->getEventManager()->trigger('post'.__FUNCTION__,$entity,$where);
        return true;
    }

    /**
     *
     * @return multitype:multitype:array  multitype:multitype:multitype:string unknown
     */
    public function getMetaDataInfo(){

        $em     =   $this->getEntityManager();
        $cmf    =   $em->getMetadataFactory();

        //var_dump($em);
        //die();


        $class  =   $cmf->getMetadataFor($this->getEntity());



        /*$table  	= $metaData->getTable($this->table);
        $columns 	= $metaData->getColumns($this->table);
        $contraints = $metaData->getConstraints($this->table);
        $views 		= $metaData->getViews();*/

        //var_dump($class);
        $columns        = array();
        $contraints     = array();
        $associations   = array();
        foreach ($class->fieldMappings as $key => $fieldMap){
            $columns[$key]=array('name'=>$fieldMap['fieldName'],'datatype'=>strtoupper($fieldMap['type']));
        }

        foreach ($class->fieldMappings as $key => $fieldMap){
            if($fieldMap['id']==true)
                $contraints[$key]=array('name'=>$fieldMap['columnName'],'datatype'=>strtoupper($fieldMap['type']));
        }


        foreach ($class->associationMappings as $key => $associationMapping){
            $associations[$key] = $associationMapping;

        }


        return [["columns"=>$columns],
                ["entity_associations"=>$associations],
                ["constraints"=>$contraints]
               ];

    }


    public function getForeignKey(){
        $em     =   $this->getEntityManager();
        $cmf    =   $em->getMetadataFactory();
        $class  =   $cmf->getMetadataFor($this->getEntity());

        //associationMappings
        foreach ($class->associationMappings as $key => $relation){
            $this->foreign_key[$relation['fieldName']]=array('targetEntity'=>$relation['targetEntity']);
        }

    }
}
