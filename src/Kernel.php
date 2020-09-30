<?php

namespace App;

use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel{
    use \Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait; // use w śrdoku klasy co robi

    protected function configureContainer(\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $c): void
    {
        $c->import('../config/{services}.yaml');
    }
    
    protected function configureRoutes(\Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator $routes): void 
    {
        $routes->import('../config/{routes}/*.yaml');   
    }
    
}
