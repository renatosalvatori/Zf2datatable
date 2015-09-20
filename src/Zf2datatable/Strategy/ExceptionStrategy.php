<?php
namespace Zf2datatable\Strategy;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Http\Response as HttpResponse;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\View\Model\ViewModel;

class ExceptionStrategy implements ListenerAggregateInterface
{
    protected $template = 'error/500';

    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'onDispatchError'), -5000);
    }

    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    public function setTemplate($template)
    {
        $this->template = $template;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function onDispatchError(MvcEvent $e)
    {
        // Do nothing if the result is a response object
        $result = $e->getResult();

        if ($result instanceof Response) {
            return;
        }

        // Common view variables
        $viewVariables = array(
            'error'      => $e->getParam('error'),
            'identity'   => $e->getParam('identity'),
        );


        $error = $e->getError();
        switch($error)
        {
            case 'error-cache-filter-expired':
                $viewVariables['controller'] = $e->getParam('controller');
                $viewVariables['action']     = $e->getParam('action');
                $viewVariables['msg']        = $e->getParam('msg');
                break;
           case 'error-cache-ordering-expired':
                $viewVariables['controller'] = $e->getParam('controller');
                $viewVariables['action']     = $e->getParam('action');
                $viewVariables['msg']        = $e->getParam('msg');
                    break;
            case Application::ERROR_EXCEPTION:
                if (!($e->getParam('exception') instanceof UnAuthorizedException)) {
                    return;
                }

                $viewVariables['reason'] = $e->getParam('exception')->getMessage();
                $viewVariables['error']  = 'error-cache-filter-expired';
                $viewVariables['msg']    = $e->getParam('msg');
                break;
            default:
                /*
                 * do nothing if there is no error in the event or the error
                 * does not match one of our predefined errors (we don't want
                 * our 403.phtml to handle other types of errors)
                 */
                return;
        }

        $model = new ViewModel($viewVariables);
        $model->setTemplate($this->getTemplate());
        $e->getViewModel()->addChild($model);

        $response = $e->getResponse();
        if (!$response) {
            $response = new HttpResponse();
            $e->setResponse($response);
        }
        $response->setStatusCode(500);
    }
}

?>