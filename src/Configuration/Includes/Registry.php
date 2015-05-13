<?php

use Bleicker\Registry\Registry;

/** Doctrine Database Settings */
Registry::set('doctrine.db.default', ['driver' => 'pdo_sqlite', 'path' => __DIR__ . '/../../../db.sqlite']);

/** Paths */
Registry::set('paths.doctrine.schema.nodes', __DIR__ . "/../../../vendor/bleicker/nodes/src/Schema/Persistence");
Registry::set('paths.doctrine.schema.nodestypes', __DIR__ . "/../../../vendor/bleicker/nodetypes/src/Schema/Persistence");
Registry::set('paths.doctrine.schema.account', __DIR__ . "/../../../vendor/bleicker/account/src/Schema/Persistence");
Registry::set('paths.cache.default', __DIR__ . '/../../../cache');
Registry::set('paths.typo3.fluid.templateRootPaths.cms', __DIR__ . '/../../Private/Templates/');
Registry::set('paths.typo3.fluid.layoutRootPaths.cms', __DIR__ . '/../../Private/Layouts/');
Registry::set('paths.typo3.fluid.partialRootPaths.cms', __DIR__ . '/../../Private/Partials/');

/** Load Local Settings if exists */
if (file_exists(__DIR__ . '/Registry.local.php')) {
	include __DIR__ . '/Registry.local.php';
}
