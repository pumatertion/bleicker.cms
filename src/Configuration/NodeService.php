<?php

use Bleicker\Nodes\NodeService;
use Bleicker\Nodes\NodeServiceInterface;
use Bleicker\ObjectManager\ObjectManager;

/**
 * Register node service
 */
$nodeService = new NodeService();
ObjectManager::register(NodeServiceInterface::class, $nodeService);
