<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use ZSmarty\SmartyModel;
use Application\Model\BlogEntry;
use DOMDocument;

class ArticleController extends AbstractActionController
{

    public function routingAction()
    {
        
		$sm = new SmartyModel;
		$sm->title = "Zulworks - Introduction to ZF2 Routing";
		$sm->active_page = "Routing";
		return $sm;
    }
    
}
