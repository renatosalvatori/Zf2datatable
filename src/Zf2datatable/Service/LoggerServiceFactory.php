<?php

namespace Zf2datatable\Service;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Log\Logger;
use Zend\Log\Writer;
use Zend\Log\Filter;
use Zend\Log\Formatter;
use Zend\Log;

class LoggerServiceFactory implements FactoryInterface{

	public function createService(ServiceLocatorInterface $serviceLocator) {
		// TODO: Auto-generated method stub
		$params=$serviceLocator->get('logger_config');
		$path_filename = $params['path_filename'];




		$file_name = $params['log_filename'];
		$priority =  $params['priority'];
		$writer = new Writer\Stream($path_filename.DIRECTORY_SEPARATOR.$params['log_filename'].".txt","a");

		$formatter = new Formatter\Simple('%timestamp% | %message%');
		$filter	= new Filter\Priority((int) $priority);
		$writer->setFormatter($formatter);
		$writer->addFilter($filter);
		$logger = new Logger();
		$logger->addWriter($writer);


		return $logger;

	}

}

?>