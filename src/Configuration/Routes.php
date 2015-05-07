<?php

use Bleicker\Cms\Controller\NodeController;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Routing\ControllerRouteData;
use Bleicker\Routing\RouteInterface;
use Bleicker\Routing\RouterInterface;

/** @var RouterInterface $router */
$router = ObjectManager::get(RouterInterface::class);
$router
	->addRoute('/', 'get', new ControllerRouteData(NodeController::class, 'indexAction'))
	->addRoute('/nodemanager', 'get', new ControllerRouteData(NodeController::class, 'indexAction'))
	->addRoute('/nodemanager/choose', 'get', new ControllerRouteData(NodeController::class, 'chooseAction'))
	->addRoute('/nodemanager/choose/{parent}', 'get', new ControllerRouteData(NodeController::class, 'chooseAction'))
	->addRoute('/nodemanager/add/{nodeType}', 'get', new ControllerRouteData(NodeController::class, 'addAction'))
	->addRoute('/nodemanager/add/{reference}', 'post', new ControllerRouteData(NodeController::class, 'addWithReferenceAction'))
	->addRoute('/nodemanager/save/{nodeType}', 'post', new ControllerRouteData(NodeController::class, 'createAction'))
	->addRoute('/nodemanager/update/{node}', 'post', new ControllerRouteData(NodeController::class, 'updateAction'))
	->addRoute('/nodemanager/update/{node}', 'patch', new ControllerRouteData(NodeController::class, 'updateAction'))
	->addRoute('/nodemanager/remove/{node}', 'delete', new ControllerRouteData(NodeController::class, 'removeAction'))
	->addRoute('/nodemanager/remove/{node}', 'get', new ControllerRouteData(NodeController::class, 'removeAction'))
	->addRoute('/nodemanager/localize/{node}/{propertyName}/{locale}', 'post', new ControllerRouteData(NodeController::class, 'localizeAction'))
	->addRoute('/nodemanager/{node}', 'get', new ControllerRouteData(NodeController::class, 'showAction'));

/**
 * Prefix every registered route with /{systemLocale}
 */
$router->dispatchClosure(function (RouterInterface $router) {
	/** @var RouteInterface $route */
	foreach ($router->getRoutes() as $route) {
		$router->addRoute('/{systemLocale}'.$route->getPattern(), $route->getMethod(), $route->getData());
	}
});
