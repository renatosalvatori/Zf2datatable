<?php
namespace Zf2datatable\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\EventManager\EventManager as Zf2EventManager;
use Zf2datatable\Datagrid;
use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use Doctrine\Common\EventManager;

class DatagridFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        $config = $sm->get('config');


        if (! isset($config['zf2datatable'])) {
            throw new InvalidArgumentException('Config key "zf2datatable" is missing');
        }

        /* @var $application \Zend\Mvc\Application */
        $application = $sm->get('application');

        $grid = new Datagrid();
        $grid->setOptions($config['zf2datatable']);
        $grid->setMvcEvent($application->getMvcEvent());
        $grid->setServiceLocator($sm);
        $grid->setEventManager(new Zf2EventManager(array('Zf2datatable\Datagrid',get_class($grid))));

        if ($sm->has('translator') === true) {
                $grid->setTranslator($sm->get('translator'));
        }

        if ($sm->has('zf2datatable_logger') === true) {
            $grid->setLogger($sm->get('zf2datatable_logger'));
        }

        $grid->init();
        return $grid;
    }
}
