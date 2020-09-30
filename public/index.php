<?php

use App\Infrastructure\Ui\Controllers\dashboard\HomeController;

session_start();
require_once '../vendor/autoload.php';

$dotenv = new \Symfony\Component\Dotenv\Dotenv();
$dotenv->loadEnv('../.env');

$loader = new Twig\Loader\FilesystemLoader('../templates');
$twig = new Twig\Environment($loader, [
    'cache' => false
]);

//$route = new Symfony\Component\Routing\Route();
$routes = new Symfony\Component\Routing\RouteCollection();
$route = new Symfony\Component\Routing\Route('/home', ['controller' => 'HomeController', 'method' => 'index']);
$routes->add('home_route', $route);
$route = new Symfony\Component\Routing\Route('/home/login', ['controller' => 'HomeController', 'method' => 'login']);
$routes->add('login_route', $route);

$context = new \Symfony\Component\Routing\RequestContext();

$context->fromRequest(\Symfony\Component\HttpFoundation\Request::createFromGlobals());
$matcher = new Symfony\Component\Routing\Matcher\UrlMatcher($routes,$context);

$parameters = $matcher->match($context->getPathInfo());
var_dump($parameters);

$classStr = $parameters['controller'];
$method = $parameters['method'];
$uri = "App\\Infrastructure\\Ui\\Controllers\\dashboard\\";
$uri .= $classStr;
echo $uri;
class_exists($uri);
$class = new $uri();
$class->setTwig($twig);
$class->$method();

//$routes->add('home', new Symfony\Component\Routing\Route('/home'), [
//    'controller' => [App\Infrastructure\Ui\Controllers\dashboard\HomeController::class,'method' => 'Index']
//]);
/*
$pathUri = $_SERVER['REQUEST_URI'];
if (strpos($_SERVER['REQUEST_URI'],'?') !== false)
{
    $pathUri = substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'],'?'));
}
$pathArray = explode('/', $pathUri);
$pathArray = array_values(array_filter($pathArray, function($item) {
    return !empty($item);
}));
$baseDirPath = '../src/Infrastructure/Ui/Controllers';
getController($baseDirPath, $pathArray, 0, $twig);


function getController($currentDir, $pathArray, $iPathArray, $twig)
{
    $controllerDirList = scandir($currentDir);
    for ($i = $iPathArray; $i < count($pathArray); $i++)
    {
        if ($i < count($pathArray) - 2)
        {
            foreach($controllerDirList as $dirEntry)
            {
                $newDirPath = $currentDir . DIRECTORY_SEPARATOR . $dirEntry;
                if ($pathArray[$i] == $dirEntry && is_dir($newDirPath))
                {
                    return getController($newDirPath, $pathArray, $i + 1, $twig);
                }
            }
        }
        else 
        {
            if ($i == count($pathArray)-2)
            {
                $newDirPath = null; 
                foreach($controllerDirList as $dirEntry)
                {
                    $newPath = $currentDir . DIRECTORY_SEPARATOR . $dirEntry;
                    if (strcasecmp($pathArray[$i] . 'Controller.php', $dirEntry) === 0 && is_file($newPath))
                    {
                        $baseClassStr = 'App\\Infrastructure\\Ui\\Controllers\\';
                        $currentPathArray = array_slice($pathArray, 0, $i + 1);
                        $currentPathArray [count($currentPathArray)-1] = ucfirst($currentPathArray [count($currentPathArray)-1]);
                        $classStr = $baseClassStr . implode('\\', $currentPathArray) . 'Controller';
                        if (!empty($classStr))
                        {
                            $class = new $classStr();
                            if ($class instanceof App\Infrastructure\Ui\Controllers\AbstractController && method_exists($class, end($pathArray)))
                            {
                                $method = end($pathArray);
                                $class->setTwig($twig);
                                $class->$method();
                            }
                        }
                        
                    }
                    else if ($pathArray[$i] == $dirEntry && is_dir($newPath)) 
                    {
                        $newDirPath = $newPath;
                    }
                }
                if (!empty($newDirPath))
                {
                    return getController($newDirPath, $pathArray, $i + 1, $twig);
                }
                return null;    
            }
            else
            {
                $baseClassStr = 'App\\Infrastructure\\Ui\\Controllers\\';
                $currentPathArray = array_slice($pathArray, 0, $i + 1);
                $currentPathArray [count($currentPathArray)-1] = ucfirst($currentPathArray [count($currentPathArray)-1]);
                $classStr = $baseClassStr . implode('\\', $currentPathArray) . 'Controller';
                if (!empty($classStr))
                {
                    $class = new $classStr();
                    if ($class instanceof App\Infrastructure\Ui\Controllers\AbstractController && method_exists($class, 'index'))
                    {
                        $class->setTwig($twig);
                        $class->index();
                    }
                }

            }
        }
    }
}

*/


//var_dump(App\Controllers\dashboard\HomeController::class);
