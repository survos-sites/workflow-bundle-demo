<?php

// config/routes.php
use Survos\WorkflowBundle\Controller\WorkflowController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;


return function (RoutingConfigurator $routes) {
    $routes->add('survos_workflows', '/workflows')
        // the controller value has the format [controller_class, method_name]
        ->controller([WorkflowController::class, 'workflows'])
        // if the action is implemented as the __invoke() method of the
        // controller class, you can skip the 'method_name' part:
        // ->controller(BlogController::class)
    ;

    $routes->add('survos_workflow', '/workflow/{flowCode}')
        // the controller value has the format [controller_class, method_name]
        ->controller([WorkflowController::class, 'workflows'])
        // if the action is implemented as the __invoke() method of the
        // controller class, you can skip the 'method_name' part:
        // ->controller(BlogController::class)
    ;

};
