<?php

use Bleicker\Framework\Context\Context;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Persistence\EntityManager;
use Bleicker\Persistence\EntityManagerInterface;
use Bleicker\Registry\Registry;
use Doctrine\Common\Cache\Cache;
use Doctrine\ORM\Tools\Setup;

ObjectManager::add(EntityManagerInterface::class, function () {
	$entityManager = EntityManager::create(
		Registry::get('doctrine.db.default'),
		Setup::createYAMLMetadataConfiguration(Registry::get('paths.doctrine.schema'), !Context::isProduction(), Registry::get('paths.cache.default') . '/doctrine', ObjectManager::get(Cache::class))
	);
	ObjectManager::add(EntityManagerInterface::class, $entityManager, TRUE);
	return $entityManager;
});
