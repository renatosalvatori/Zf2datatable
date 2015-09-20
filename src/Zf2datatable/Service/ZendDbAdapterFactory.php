<?php
namespace Zf2datatable\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\Adapter\Adapter;

class ZendDbAdapterFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        $defaultAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        if($defaultAdapter instanceof \Zend\Db\Adapter\AdapterInterface){
            return $defaultAdapter;
        }

        $config = $sm->get('config');
        return new Adapter($config['zf2datatable_dbAdapter']);
    }
}
