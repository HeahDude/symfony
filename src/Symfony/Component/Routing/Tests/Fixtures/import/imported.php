<?php

namespace Symfony\Component\Routing\Loader\Configurator;

return function (RoutingConfigurator $routes) {
    $routes
        ->add('route_with_globals', '/imported-with-globals')

        ->add('route_with_specifics', '/imported-with-specifics')
        ->defaults([
            'global' => 'is_specific',
            'specific' => 'test',
        ])
        ->requirements([
            'global' => 'should_be_specific',
            'specific' => 'should_be_test',
        ])
        ->options(['utf8' => false])
        ->host('specific_host')
        ->condition('def')
        ->schemes(['http'])
        ->methods(['POST'])
    ;
};
