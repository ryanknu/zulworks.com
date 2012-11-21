<?php

/**
 * ZulProfiler
 * Profiling tool for Zend Framework 2
 */

namespace ZulProfiler;

use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $app = $e->getApplication();
        if ( $app instanceof Application )
        {
            Profiler::profiler()->startDispatch();
            
            $event_manager = $app->getEventManager();
            $event_manager->attach(MvcEvent::EVENT_RENDER, array($this, 'onRender'));
            $event_manager->attach(MvcEvent::EVENT_FINISH, array($this, 'onFinish'));
        }
    }
    
    public function onRender(MvcEvent $e)
    {
        Profiler::profiler()->startRender();
    }
    
    public function onFinish(MvcEvent $e)
    {
        Profiler::profiler()->finish();
    }
}
