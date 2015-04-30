<?php
function includeIfExists($file) {
	return file_exists($file) ? include $file : FALSE;
}

/** @var \Composer\Autoload\ClassLoader $autoloader */
if ((!$classLoader = includeIfExists(__DIR__ . '/../vendor/autoload.php')) && (!$classLoader = includeIfExists(__DIR__ . '/../../../autoload.php'))) {
	echo 'You must set up the project dependencies, run the following commands:' . PHP_EOL .
		'curl -sS https://getcomposer.org/installer | php' . PHP_EOL .
		'php composer.phar install' . PHP_EOL;
	exit(1);
}

$classLoader->addPsr4('Tests\\Bleicker\\Cms\\', __DIR__ . '/');
