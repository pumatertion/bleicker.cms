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
	ObjectManager::register(FluidCacheInterface::class, new SimpleFileCache(ROOT_DIRECTORY . '/cache/Fluid'));
}

ObjectManager::register(Cache::class, function () {
	return new FilesystemCache(ROOT_DIRECTORY . '/cache/Doctrine');
});
