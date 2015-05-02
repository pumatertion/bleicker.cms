<?php

use Bleicker\Cms\Controller\NodeController;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Routing\ControllerRouteData;
use Bleicker\Routing\RouterInterface;

/** @var RouterInterface $router */
$router = ObjectManager::get(RouterInterface::class);
$router
	->addRoute('/nodemanager', 'get', new ControllerRouteData(NodeController::class, 'indexAction'))
	->addRoute('/nodemanager/{node}', 'get', new ControllerRouteData(NodeController::class, 'showAction'));
