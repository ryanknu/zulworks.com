<?php

namespace Hue\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use ZSmarty\SmartyModel;

// post body: $this->getRequest()->getContent();

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $zhue = $this->getServiceLocator()->get('ZHue');
        $this->layout('hue/layout');
        $sm = new SmartyModel;
        
        $sm->lights = $zhue->getLights()->lights;
        
        return $sm;
    }
    
    public function renameAction()
    {
	    $zhue = $this->getServiceLocator()->get('ZHue');
        $sm = new SmartyModel;
        
        $sm->lights = $zhue->getLights()->lights;
        $sm->setTerminal(true);
        
        return $sm;
    }
}
