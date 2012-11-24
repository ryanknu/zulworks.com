<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use ZSmarty\SmartyModel;

use Zule\Rs\Player;
use Zule\Rs\EventType;
use Zule\Rs\Cache;

class ZulController extends AbstractActionController
{
    public function indexAction()
    {
        // Set cache control - in our case /data will refresh the cache
        Cache::$alwaysUseCache = true;
        
        $sm = $this->dataAction();
        $sm->setTerminal(false);
        return $sm;
    }
    
    public function dataAction()
    {
        $sm = new SmartyModel;
        
        $sm->active_page = 'Zul';

        $p = new Player('the ZUL');
        $sm->image = sprintf('<img style="float:left;" src="%s" />', 
            $p->getCharacterImageHttpLocation());
        
        $events = $p->events->getEventsOfType(EventType::LevelUp);
        
        $levels = '';
        for ($i = 0; $i < 4; $i++)
        {
            $levels .= sprintf('%s <br />', $events[$i]->description);
        }
        
        $sm->levels = $levels;
        
        $sm->setTerminal(true);
        return $sm;
    }
}
