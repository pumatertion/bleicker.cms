<?php

/**
 * Register node service
 */
use Bleicker\Nodes\NodeService;
use Bleicker\Nodes\NodeServiceInterface;
use Bleicker\ObjectManager\ObjectManager;

$nodeService = new NodeService();
ObjectManager::register(NodeServiceInterface::class, $nodeService);