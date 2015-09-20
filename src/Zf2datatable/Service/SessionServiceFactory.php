<?php
namespace Zf2datatable\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\SessionManager;
use Zend\Session\Config\SessionConfig;
use Zf2datatable\Session\Zf2Container as Container;

class SessionServiceFactory implements FactoryInterface{

	const CONTAINER ='zf2SessionContainer';

	public function createService(ServiceLocatorInterface $serviceLocator){
		$config = $serviceLocator->get('custom_namespace');
		if(isset($config['container'])){
			$container = $config['container'];
		}
		else
			$container = self::CONTAINER;

		return new Container($container);
	}

}
?>