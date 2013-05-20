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
                    'route'    => '/collab',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Collaborate\Controller',
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
                    'get-key' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/get-key',
                            'defaults' => array(
                                'action' => 'getKey',
                            ),
                        ),
                    ),
                    'get-balance' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/get-balance',
                            'defaults' => array(
                                'action' => 'getBalance',
                            ),
                        ),
                    ),
                    'get-ledger' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/get-ledger',
                            'defaults' => array(
                                'action' => 'getLedger',
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
            'Collaborate\Controller\Index' => 'Collaborate\Controller\IndexController',
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'collaborate/index/index' => __DIR__ . '/../view/index.tpl',
        ),
        
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
