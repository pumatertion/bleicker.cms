<?php

use Bleicker\Registry\Registry;

Registry::set('DbConnection', ['driver' => 'pdo_sqlite', 'path' => ROOT_DIRECTORY . '/Private/db.sqlite']);

if(file_exists(__DIR__.'/Secrets.local.php')){
	include __DIR__ . '/Secrets.local.php';
}
