<?php

namespace Symfony\Component\Routing\Loader\Configurator;

return function (RoutingConfigurator $routes) {
    $routes->import('imported.php', 'php')
        ->defaults([
            'global' => 'is_global',
        ])
        ->requirements([
            'global' => 'should_be_global',
        ])
        ->options(['utf8' => true])
        ->host('global_host')
        ->condition('abc')
        ->schemes(['https'])
        ->methods(['GET'])
    ;
};
