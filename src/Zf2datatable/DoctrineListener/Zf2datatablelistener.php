<?php
namespace Zf2datatable\DoctrineListener;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use AdminApplication\Model\Entity\Languages;

use Doctrine\Common\EventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;


class Zf2datatablelistener implements ServiceLocatorAwareInterface
{

    protected $serviceLocator;

    public function __construct()
    {
        //die('call');
    }

    /**
     *
     * @return the $serviceLocator
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }


    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     *
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $eventArgs
     */
    public function preRemove(EventArgs $event)
    {
        echo(__FUNCTION__).':in listener';

    }

    /**

     *
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $eventArgs
     */
    public function postRemove(EventArgs $event)
    {
        echo(__FUNCTION__).':in listener';
        die();
    }

    /**
     *
     *@param \Doctrine\ORM\Event\PreUpdateEventArgs $eventArgs
     **/
    public function prePersist(LifecycleEventArgs $event)
    {
        echo(__FUNCTION__).':in listener';
        //die();
        //var_dump($event->getEntity());

        //die();
    }

    public function postPersist(LifecycleEventArgs $event)
    {
        echo(__FUNCTION__).':in listener';
        //die();
    }

    public function preUpdate(LifecycleEventArgs $event)
    {
        echo(__FUNCTION__).':in listener';
        //die();
    }

    public function postUpdate(LifecycleEventArgs $event)
    {
        echo(__FUNCTION__).':in listener';
        //die();
    }

    public function loadClassMetadata(EventArgs $event)
    {
        echo(__FUNCTION__).':in listener';
        //die();
    }

    public function onFlush(OnFlushEventArgs $event)
    {
        echo(__FUNCTION__).':in listener';
        //die();
    }

    public function preFlush(PreFlushEventArgs $event)
    {
        echo(__FUNCTION__).':in listener';
        //die();
    }

    public function postFlush(PostFlushEventArgs $event)
    {
        echo(__FUNCTION__).':in listener';
        //die();
    }

    public function postLoad(EventArgs $event)
    {
        //echo(__FUNCTION__).':in listener:'.var_dump($event->getEntityManager())."<br />";
        //var_dump($event->getEntity());
        //echo(__FUNCTION__);
        //die();
    }


}

?>