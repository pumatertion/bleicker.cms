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
	ObjectManager::register(FluidCacheInterface::class, function () {
		return new SimpleFileCache(realpath(__DIR__ . '/../../cache/typo3.fluid'));
	});
}

ObjectManager::register(Cache::class, function () {
	return new FilesystemCache(realpath(__DIR__ . '/../../cache/doctrine'));
});
