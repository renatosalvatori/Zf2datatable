<?php
namespace Zf2datatable\DataSource;

use Zf2datatable\Column;
use Zf2datatable\Filter;
use Zend\Paginator\Adapter\AdapterInterface as PaginatorAdapterInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Doctrine\Common\EventManager;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

abstract class AbstractDataSource implements DataSourceInterface, EventManagerAwareInterface,ServiceLocatorAwareInterface
{

    /**
     *
     * @var array
     */
    protected $columns = array();

    /**
     *
     * @var array
     */
    protected $sortConditions = array();

    /**
     *
     * @var array
     */
    protected $filters = array();

    /**
     * The data result
     *
     * @var \Zend\Paginator\Adapter\AdapterInterface
     */
    protected $paginatorAdapter;


    /**
     *
     * @var Zend\EventManager\EventManagerInterface
     */
    protected $eventManager;


    /**
     *
     * @var Zend\ServiceManager\Servicelocator
     */
    protected $serviceLocator;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var string
     */
    protected $alias_table;

    /**
     * @var string
     */
    protected $entity;

    /**
     *
     * @var \Zend\Cache\Storage\StorageInterface
     */
    protected $cache;

    /**
     *
     * @var array
     */
    protected $option;



    /**
     * @return the $option
     */
    public function getOption()
    {
        return $this->option;
    }

	/**
     * @param multitype: $option
     */
    public function setOption($option)
    {
        $this->option = $option;
    }

	/**
     * @return the $cache
     */
    public function getCache()
    {
        return $this->cache;
    }

	/**
     * @param \Zend\Cache\Storage\StorageInterface $cache
     */
    public function setCache(\Zend\Cache\Storage\StorageInterface $cache)
    {
        $this->cache = $cache;
    }

	/**
     * @return the $entity
     */
    public function getEntity()
    {
        return isset($this->entity) ? $this->entity : '\ArrayObject';
    }

	/**
     * @param string $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

	/**
	 * @return the $eventManager
	 */
	public function getEventManager() {
		if($this->eventManager == null){
			$this->eventManager = new \Zend\EventManager\EventManager(__CLASS__);
		}
		return $this->eventManager;
	}

	/**
	 * @param field_type $eventManager
	 */
	public function setEventManager(EventManagerInterface $eventManager) {
		$this->eventManager = $eventManager;
	}

	/**
	 * Set service locator
	 *
	 * @param ServiceLocatorInterface $serviceLocator
	 */
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
	    $this->serviceLocator = $serviceLocator;
	}

	/**
	 * Get service locator
	 *
	 * @return ServiceLocatorInterface
	 */
	public function getServiceLocator() {
	    return $this->serviceLocator;
	}


	/**
	 * @return the $table
	 */
	public function getTable() {
		return $this->table;
	}

	public function getTableAlias() {
	    return $this->alias_table;
	}

	/**
	 * @param string $table
	 */
	public function setTable($table,$alias_table) {
		$this->table = $table;
		$this->alias_table = $alias_table;
		return $this;
	}


	/**
     * Set the columns
     *
     * @param array $columns
     */
    public function setColumns(array $columns)
    {
        $this->columns = $columns;
    }

    /**
     *
     * @return Column\AbstractColumn[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Set sort conditions
     *
     * @param Column\AbstractColumn $column
     * @param string                $sortDirection
     */
    public function addSortCondition(Column\AbstractColumn $column, $sortDirection = 'ASC')
    {
        $this->sortConditions[] = array(
            'column' => $column,
            'sortDirection' => $sortDirection
        );
    }

    public function setSortConditions(array $sortConditions)
    {
        $this->sortConditions = $sortConditions;
    }

    /**
     *
     * @return array
     */
    public function getSortConditions()
    {
        return $this->sortConditions;
    }

    /**
     * Add a filter rule
     *
     * @param Filter $filter
     */
    public function addFilter(Filter $filter)
    {
        $this->filters[] = $filter;
    }

    public function setFilters(array $filters)
    {
        $this->filters = $filters;
    }

    /**
     *
     * @return \ZfcDatagrid\Filter[]
     */
    public function getFilters()
    {
        return $this->filters;
    }

    public function setPaginatorAdapter(PaginatorAdapterInterface $paginator)
    {
        $this->paginatorAdapter = $paginator;
    }

    /**
     *
     * @return \Zend\Paginator\Adapter\AdapterInterface
     */
    public function getPaginatorAdapter()
    {
        return $this->paginatorAdapter;
    }

    /**
     *
     */
    protected function _init(){
        // to implemet
    }


    abstract public function getDefaultBindObject();


}
