<?php

use Bleicker\Registry\Registry;

Registry::set('typo3.fluid.templateRootPaths.cms', realpath(__DIR__ . '/../Private/Templates') . DIRECTORY_SEPARATOR);
Registry::set('typo3.fluid.layoutRootPaths.cms', realpath(__DIR__ . '/../Private/Layouts') . DIRECTORY_SEPARATOR);
Registry::set('typo3.fluid.partialRootPaths.cms', realpath(__DIR__ . '/../Private/Partials') . DIRECTORY_SEPARATOR);
