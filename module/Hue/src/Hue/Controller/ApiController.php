<?php

namespace Hue\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use ZSmarty\SmartyModel;
use ZHue\Color;

// post body: $this->getRequest()->getContent();

class ApiController extends AbstractActionController
{
    public function statusAction()
    {
        $zhue = $this->getServiceLocator()->get('ZHue');
//        $this->layout('hue/layout');
//        $sm = new SmartyModel;
//        $sm->setTerminal(true);
        
        $lc = $zhue->getLights();
        $lights = $lc->lights;
        foreach($lights as $light) {
	        if ( $light->id == $_POST['light_id']) {
	        	if ( isset( $_POST['color'] )) {
	        		if ( $o = json_decode($_POST['color'])) {
		        		
	        		}
	        		else {
		        		$o = $_POST['color'];
	        		}
	        		
			        $c = new Color($o);
			        $light->color = $c;
			        $lc->setState($light);
		        }
		        else if ( isset( $_POST['state'] ) ) {
			        $light->on = $_POST['state'] == 'on';
			        $lc->switchLight($light);
		        }
		        else if ( isset( $_POST['name'] ) ) {
			        $light->name = $_POST['name'];
			        $lc->renameLight($light);
		        }
	        }
        }
        
        return new JsonModel(array('something' => 'else'));
    }
    
    public function cycleAction()
    {
    	$zhue = $this->getServiceLocator()->get('ZHue');
    	
	    $light = $_POST['light_id'];
	    $cycle = $zhue->getStore()->get('Cycle-' . $light);

	    if ( ! $cycle ) {
		    $cycle = array();
	    }
	    
	    if ( isset($_POST['time']) ) {
		    $time = $_POST['time'];
		    $color = $_POST['color'];
		    
		    $cycle[$time / 500] = (object) array(
		    	'time' => $time,
		    	'color' => $color,
		    );
		    $zhue->getStore()->persist('Cycle-' . $light, $cycle);
	    }
	    
	    return new JsonModel(array('light' => $light, 'cycle' => $cycle));
    }
}
