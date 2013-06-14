<?php

namespace Despesa; // referente ao doctrine

return array(
    # definir controllers
    'controllers' => array(
        'invokables' => array(
            'HomeController' => 'Despesa\Controller\HomeController',
            'DespesasController' => 'Despesa\Controller\DespesasController',
        ),
    ),

    # definir rotas
    'router' => array(
        'routes' => array(
            'home' => array(
                'type'      => 'Literal',
                'options'   => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'HomeController',
                        'action'     => 'index',
                    ),
                ),
            ),
            'despesas' => array(
                'type'      => 'segment',
                'options'   => array(
                    'route'    => '/despesas[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'DespesasController',
                        'action'     => 'index',
                    ),
                ),
            ),           
        ),
    ),

    # definir gerenciador de servicos
    'service_manager' => array(
        'factories' => array(
            // temos que comentar esta linha para funcionar o sistema
           // 'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        ),
    ),

    # definir layouts, erros, exceptions, doctype base
    'view_manager' => array(
        'display_not_found_reason'  => true,
        'display_exceptions'        => true,
        'doctype'                   => 'HTML5',
        'not_found_template'        => 'error/404',
        'exception_template'        => 'error/index',
        'template_map'              => array(
            'layout/layout'         => __DIR__ . '/../view/layout/layout.phtml',
            'despesa/home/index'    => __DIR__ . '/../view/despesa/home/index.phtml',
            'error/404'             => __DIR__ . '/../view/error/404.phtml',
            'error/index'           => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    

    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    )


);
