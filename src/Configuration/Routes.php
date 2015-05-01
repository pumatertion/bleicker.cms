<?php

use Bleicker\Cms\Controller\NodeController;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Routing\ControllerRouteData;
use Bleicker\Routing\RouterInterface;

/** @var RouterInterface $router */
$router = ObjectManager::get(RouterInterface::class);
$router
	->addRoute('/manager', 'get', new ControllerRouteData(NodeController::class, 'indexAction'))
	->addRoute('/manager/{node}', 'get', new ControllerRouteData(NodeController::class, 'indexAction'));

