<?php

include __DIR__ . '/../vendor/autoload.php';

use Bleicker\Framework\ApplicationFactory;

ApplicationFactory::http(function(){
	include __DIR__ . '/../src/Configuration/Web.php';
})->run();
