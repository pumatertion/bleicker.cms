<?php

use Bleicker\Framework\Context\Context;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Persistence\EntityManager;
use Bleicker\Persistence\EntityManagerInterface;
use Bleicker\Registry\Registry;
use Doctrine\Common\Cache\Cache;
use Doctrine\ORM\Tools\Setup;

Registry::set('doctrine.schema.paths.nodes', realpath(__DIR__ . "/../../vendor/bleicker/nodes/src/Schema/Persistence"));
Registry::set('doctrine.schema.paths.nodestypes', realpath(__DIR__ . "/../../vendor/bleicker/nodetypes/src/Schema/Persistence"));
Registry::set('doctrine.schema.paths.account', realpath(__DIR__ . "/../../vendor/bleicker/account/src/Schema/Persistence"));

ObjectManager::add(EntityManagerInterface::class, function () {
	$entityManager = EntityManager::create(
		Registry::get('DbConnection'),
		Setup::createYAMLMetadataConfiguration(Registry::get('doctrine.schema.paths'), !Context::isProduction(), __DIR__ . '/../../cache/doctrine', ObjectManager::get(Cache::class))
	);
	ObjectManager::add(EntityManagerInterface::class, $entityManager, TRUE);
	return $entityManager;
});
