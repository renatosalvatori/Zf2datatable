<?php
namespace Zf2datatable\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Console\Input\StringInput;
use AssetManager\Exception\InvalidArgumentException;
use Zend\Mvc\Router\Http\Method;


class Doctrine2Service  implements ServiceLocatorAwareInterface
{

    private $serviceLocator;
    protected $entityManager;

    /**
     * @return the $entityManager
     */
    public function getEntityManager()
    {
        if(null === $this->entityManager)
            $this->entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_zfcDatagrid');

        return $this->entityManager;
    }

	/**
     * @param field_type $entityManager
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }

	/**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }


    /**
     *
     * @param strng $class_name
     * @throws InvalidArgumentException
     * @return unknown
     */
    public function getRepository($class_name){
            return $this->getEntityManager()->getRepository($class_name);

    }

    /**
     *
     * @param string $class_name
     * @throws InvalidArgumentException
     * @return unknown
     */
    public function getEntity($class_name){
        if(class_exists($class_name)){
            $entity = new $class_name;
            return $entity;
        }
        else
            throw new InvalidArgumentException('No Classfonud', $code, $previous);


    }

    /**
     *
     * @param string $className
     * @param string $method
     */
    public function __call($method='someMethod',$params=array()){
        $arg =func_get_args();
        $qb = $this->getEntityManager()->createQueryBuilder();


        if($arg[1][0] instanceof \Doctrine\ORM\QueryBuilder)
            return $arg[1][0];

        $qb->select($arg[1][1]);
        $qb->from($arg[1][0], $arg[1][1]);
        return $qb;
    }







}

?>