<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/_profiler' => [[['_route' => '_profiler_home', '_controller' => 'web_profiler.controller.profiler::homeAction'], null, null, null, true, false, null]],
        '/_profiler/search' => [[['_route' => '_profiler_search', '_controller' => 'web_profiler.controller.profiler::searchAction'], null, null, null, false, false, null]],
        '/_profiler/search_bar' => [[['_route' => '_profiler_search_bar', '_controller' => 'web_profiler.controller.profiler::searchBarAction'], null, null, null, false, false, null]],
        '/_profiler/phpinfo' => [[['_route' => '_profiler_phpinfo', '_controller' => 'web_profiler.controller.profiler::phpinfoAction'], null, null, null, false, false, null]],
        '/_profiler/open' => [[['_route' => '_profiler_open_file', '_controller' => 'web_profiler.controller.profiler::openAction'], null, null, null, false, false, null]],
        '/home' => [[['_route' => 'home', '_controller' => 'App\\Infrastructure\\Ui\\Controllers\\dashboard\\HomeController::index'], null, null, null, false, false, null]],
        '/home/loggedin' => [[['_route' => 'loggedin', '_controller' => 'App\\Infrastructure\\Ui\\Controllers\\dashboard\\HomeController::loggedin'], null, null, null, false, false, null]],
        '/pogoda' => [[['_route' => 'pogoda', '_controller' => 'App\\Infrastructure\\Ui\\Controllers\\dashboard\\PogodaController::index'], null, null, null, false, false, null]],
        '/home/Reports' => [[['_route' => 'Reports', '_controller' => 'App\\Infrastructure\\Ui\\Controllers\\dashboard\\ReportController::reports'], null, null, null, false, false, null]],
        '/home/addResults' => [[['_route' => 'addResults', '_controller' => 'App\\Infrastructure\\Ui\\Controllers\\dashboard\\ResultController::addResults'], null, null, null, false, false, null]],
        '/home/showAllResults' => [[['_route' => 'showAllResults', '_controller' => 'App\\Infrastructure\\Ui\\Controllers\\dashboard\\ResultController::showAllResults'], null, null, null, false, false, null]],
        '/home/ranking' => [[['_route' => 'ranking', '_controller' => 'App\\Infrastructure\\Ui\\Controllers\\dashboard\\ResultController::ranking'], null, null, null, false, false, null]],
        '/home/addResults/MatchAddedForUser' => [[['_route' => 'matchAddedForUser', '_controller' => 'App\\Infrastructure\\Ui\\Controllers\\dashboard\\ResultController::MatchAddedForUser'], null, null, null, false, false, null]],
        '/home/logout' => [[['_route' => 'logout', '_controller' => 'App\\Infrastructure\\Ui\\Controllers\\dashboard\\SecurityController::logout'], null, null, null, false, false, null]],
        '/home/login' => [[['_route' => 'login', '_controller' => 'App\\Infrastructure\\Ui\\Controllers\\dashboard\\SecurityController::login'], null, null, null, false, false, null]],
        '/home/register' => [[['_route' => 'register', '_controller' => 'App\\Infrastructure\\Ui\\Controllers\\dashboard\\SecurityController::register'], null, null, null, false, false, null]],
        '/test' => [[['_route' => 'asc', '_controller' => 'App\\Infrastructure\\Ui\\Controllers\\dashboard\\TestController::test'], null, null, null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_(?'
                    .'|error/(\\d+)(?:\\.([^/]++))?(*:38)'
                    .'|wdt/([^/]++)(*:57)'
                    .'|profiler/([^/]++)(?'
                        .'|/(?'
                            .'|search/results(*:102)'
                            .'|router(*:116)'
                            .'|exception(?'
                                .'|(*:136)'
                                .'|\\.css(*:149)'
                            .')'
                        .')'
                        .'|(*:159)'
                    .')'
                .')'
                .'|/reports/getReports/([^/]++)/([^/]++)(*:206)'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        38 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        57 => [[['_route' => '_wdt', '_controller' => 'web_profiler.controller.profiler::toolbarAction'], ['token'], null, null, false, true, null]],
        102 => [[['_route' => '_profiler_search_results', '_controller' => 'web_profiler.controller.profiler::searchResultsAction'], ['token'], null, null, false, false, null]],
        116 => [[['_route' => '_profiler_router', '_controller' => 'web_profiler.controller.router::panelAction'], ['token'], null, null, false, false, null]],
        136 => [[['_route' => '_profiler_exception', '_controller' => 'web_profiler.controller.exception_panel::body'], ['token'], null, null, false, false, null]],
        149 => [[['_route' => '_profiler_exception_css', '_controller' => 'web_profiler.controller.exception_panel::stylesheet'], ['token'], null, null, false, false, null]],
        159 => [[['_route' => '_profiler', '_controller' => 'web_profiler.controller.profiler::panelAction'], ['token'], null, null, false, true, null]],
        206 => [
            [['_route' => 'getReports', '_controller' => 'App\\Infrastructure\\Ui\\Controllers\\dashboard\\ReportController::getReports'], ['reportName', 'reportOutput'], ['GET' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
