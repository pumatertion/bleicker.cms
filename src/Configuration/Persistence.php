<?php

use Bleicker\Framework\Context\Context;
use Bleicker\Registry\Registry;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Persistence\EntityManager;
use Bleicker\Persistence\EntityManagerInterface;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\FilesystemCache as CacheImplementation;
use Doctrine\ORM\Tools\Setup;

Registry::set('doctrine.schema.paths.nodes', __DIR__ . "/../../vendor/bleicker/nodes/src/Schema/Persistence");
Registry::set('doctrine.schema.paths.nodestypes', __DIR__ . "/../../vendor/bleicker/nodestypes/src/Schema/Persistence");

ObjectManager::register(Cache::class, function () {
	return new CacheImplementation(realpath(__DIR__ . '/../../cache/Doctrine'));
});

ObjectManager::register(EntityManagerInterface::class, function () {
	return EntityManager::create(
		Registry::get('DbConnection'),
		Setup::createYAMLMetadataConfiguration(Registry::get('doctrine.schema.paths'), !Context::isProduction(), realpath(__DIR__ . '/../../cache/Proxies'), ObjectManager::get(Cache::class))
	);
});
