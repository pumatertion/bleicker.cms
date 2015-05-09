<?php

use Bleicker\Framework\Context\Context;
use Bleicker\ObjectManager\ObjectManager;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\FilesystemCache;
use TYPO3\Fluid\Core\Cache\FluidCacheInterface;
use TYPO3\Fluid\Core\Cache\SimpleFileCache;

/**
 * Cache Configurations
 */
if (Context::isProduction()) {
	ObjectManager::add(FluidCacheInterface::class, function () {
		$fluidCache = new SimpleFileCache(realpath(__DIR__ . '/../../cache/typo3.fluid'));
		ObjectManager::add(FluidCacheInterface::class, $fluidCache, TRUE);
		return $fluidCache;
	});
}

ObjectManager::add(Cache::class, function () {
	$doctrineCache = new FilesystemCache(realpath(__DIR__ . '/../../cache/doctrine'));
	ObjectManager::add(Cache::class, $doctrineCache, TRUE);
	return $doctrineCache;
});
