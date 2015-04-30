<?php

use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Persistence\EntityManager;
use Bleicker\Persistence\EntityManagerInterface;
use Bleicker\Registry\Registry;
use Doctrine\ORM\Tools\Setup;

Registry::set('doctrine.schema.paths', array());

ObjectManager::register(EntityManagerInterface::class, function () {
	return EntityManager::create(
		Registry::get('DbConnection'),
		Setup::createYAMLMetadataConfiguration(Registry::get('doctrine.schema.paths'), TRUE)
	);
});
