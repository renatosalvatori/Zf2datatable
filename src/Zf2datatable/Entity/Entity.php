<?php
namespace Zf2datatable\Entity;
use Zend\Form\Annotation;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class Entity implements ServiceLocatorAwareInterface {
	
	/**
	 *
	 * @var ServiceLocatorInterface
	 */
	private $serviceLocator;
	
	
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
	
	
}

?>