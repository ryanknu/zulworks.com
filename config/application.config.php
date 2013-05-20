<?php
return array(
    'modules' => array(
        'Application',
        'ZSmarty',
        'Less',
        'Collaborate',
        'ZuleRs',
        'Hue',
        'ZHue',
        'Guzzle',
        'Symfony',
        // 'Hyperlight',
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
        'module_paths' => array(
            './module',
            './vendor',
        ),
    ),
);
