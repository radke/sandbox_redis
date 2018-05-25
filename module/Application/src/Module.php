<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\MvcEvent;
use Zend\Session\SessionManager;
use Zend\Session\Container;

class Module
{
    const VERSION = '3.0.3-dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * This method is called once the MVC bootstrapping is complete.
     */
    public function onBootstrap(MvcEvent $event)
    {
        $application = $event->getApplication();

        $serviceManager = $application->getServiceManager();
        $sessionManager = $serviceManager->get(SessionManager::class);

        $this->forgetInvalidSession($sessionManager);

        $sessionContainer = new Container('ContainerNamespace', $sessionManager);
        $sessionContainer->something = 'Something very important';

        if (isset($sessionContainer->something)) {
            $myVar = $sessionContainer->something;
        } else {
            $myVar = null;
        }

        unset($sessionContainer->something);
    }

    protected function forgetInvalidSession($sessionManager)
    {
        try {
            $sessionManager->start();
            return;
        } catch (\Exception $e) {}

        session_unset();
    }
}
