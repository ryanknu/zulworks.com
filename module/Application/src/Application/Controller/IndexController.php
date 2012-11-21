<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ZSmarty\SmartyModel;
use Less\Less;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
    	$less = new Less;
    	$less->setFormatter("compressed");
        $less->compileFile('public/css/less/zulworks.less', 'public/css/zulworks.css');
    	
        $sm = new SmartyModel;
        $sm->title = "zulworks - web development adventures";
        $sm->active_page = "Home";
        return $sm;
    }
}
