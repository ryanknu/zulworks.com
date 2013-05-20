<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'collab' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/hue',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Hue\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'get-db' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/get-db',
                            'defaults' => array(
                                'action' => 'getDb',
                            ),
                        ),
                    ),
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                        'priority' => -1,
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Hue\Controller\Index' => 'Hue\Controller\IndexController',
            'Hue\Controller\Api'   => 'Hue\Controller\ApiController',
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'hue/layout'       => __DIR__ . '/../view/layout/layout.tpl',
            'hue/index/index'  => __DIR__ . '/../view/index/index.tpl',
            'hue/index/rename' => __DIR__ . '/../view/index/rename.tpl',
        ),
        
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
