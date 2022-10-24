<?php

// config/routes.php
use Survos\CommandBundle\Controller\CommandController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;


return function (RoutingConfigurator $routes) {
    $routes->add('survos_workflows', '/')
        // the controller value has the format [controller_class, method_name]
        ->controller([CommandController::class, 'commands'])
    ;

    $routes->add('run_command', '/run-command/{commandName}')
        // the controller value has the format [controller_class, method_name]
        ->controller([CommandController::class, 'commands'])
    ;

};
