<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use ZSmarty\SmartyModel;
use Application\Model\BlogEntry;
use DOMDocument;

class BlogController extends AbstractActionController
{
    const Feed = 'http://ryanknu.tumblr.com/rss';

    public function latestAction()
    {
		$domdoc = new DOMDocument;
		$domdoc->load(self::Feed);
		
		$items = $domdoc->getElementsByTagName('item');

		$blogs = array();
		
		foreach($items as $item)
		{
    		$be = new BlogEntry;
    		foreach($item->childNodes as $childNode)
    		{
        		if ( $childNode->nodeName == 'title' )
        		{
            		$be->title = $childNode->textContent;
        		}
        		else if ( $childNode->nodeName == 'description' )
        		{
            		$be->text = $childNode->textContent;
        		}
        		else if ( $childNode->nodeName == 'pubDate' )
        		{
            		$be->date = $childNode->textContent;
        		}
    		}
    		$blogs[] = $be;
		}
		
		$sm = new SmartyModel;
		$sm->blogs = $blogs;
		$sm->active_page = 'Home';
		$sm->setTerminal(true);
		return $sm;
    }

    public function blogAction()
    {
		$sm = $this->latestAction();
		$sm->active_page = 'Blog';
		$sm->setTerminal(false);
		return $sm;
    }
    
}
