<?php

use Bleicker\Registry\Registry;

Registry::set('typo3.fluid.templateRootPaths.test', realpath(__DIR__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Private' . DIRECTORY_SEPARATOR . 'Templates');
Registry::set('typo3.fluid.layoutRootPaths.test', realpath(__DIR__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Private' . DIRECTORY_SEPARATOR . 'Layouts');
Registry::set('typo3.fluid.partialRootPaths.test', realpath(__DIR__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Private' . DIRECTORY_SEPARATOR . 'Partials');
