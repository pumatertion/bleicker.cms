<?php

use Bleicker\Cms\Controller\NodeController;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Routing\ControllerRouteData;
use Bleicker\Routing\RouterInterface;

/** @var RouterInterface $router */
$router = ObjectManager::get(RouterInterface::class);
$router
	->addRoute('/nodemanager', 'get', new ControllerRouteData(NodeController::class, 'indexAction'))
	->addRoute('/nodemanager/choose', 'get', new ControllerRouteData(NodeController::class, 'chooseAction'))
	->addRoute('/nodemanager/choose/{parent}', 'get', new ControllerRouteData(NodeController::class, 'chooseAction'))
	->addRoute('/nodemanager/add/{nodeType}', 'get', new ControllerRouteData(NodeController::class, 'addAction'))
	->addRoute('/nodemanager/save/{nodeType}', 'post', new ControllerRouteData(NodeController::class, 'createAction'))
	->addRoute('/nodemanager/update/{node}', 'post', new ControllerRouteData(NodeController::class, 'updateAction'))
	->addRoute('/nodemanager/update/{node}', 'patch', new ControllerRouteData(NodeController::class, 'updateAction'))
	->addRoute('/nodemanager/{node}', 'get', new ControllerRouteData(NodeController::class, 'showAction'))
	->addRoute('/nodemanager/{node}', 'patch', new ControllerRouteData(NodeController::class, 'updateAction'));
