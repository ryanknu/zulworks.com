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
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'blog' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/blog',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Blog',
                        'action' => 'blog',
                    ),
                ),
                'child_routes' => array(
                    'latest' => array(
                        'type'    => 'Literal',
                        'priority' => 2,
                        'options' => array(
                            'route'    => '/latest',
                            'defaults' => array(
                                'action' => 'latest',
                            ),
                        ),
                    ),
                    'default' => array(
                        'type'    => 'Wildcard',
                        'options' => array(
                            'defaults' => array(
                                'action' => 'blog',
                            ),
                        ),
                    ),
                ),
            ),
            'zf' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/zf',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Article',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:action',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                        ),
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
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
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Smarty' => 'ZSmarty\StrategyFactory',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Article' => 'Application\Controller\ArticleController',
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Blog' => 'Application\Controller\BlogController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true, // In production, please set to false.
        'display_exceptions'       => true, // This website is open source, though.
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'               => __DIR__ . '/../view/layout.tpl',
            'application/index/index'     => __DIR__ . '/../view/index.tpl',
            'application/blog/blog'       => __DIR__ . '/../view/blog.tpl',
            'application/blog/latest'     => __DIR__ . '/../view/latest.tpl',
            'application/article/routing' => __DIR__ . '/../view/article-routing.tpl',
            'error/404'                   => __DIR__ . '/../view/error/404.phtml',
            'error/index'                 => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
        	'Smarty'
        )
    ),
);
