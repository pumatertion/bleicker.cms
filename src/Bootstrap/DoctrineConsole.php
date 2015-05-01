<?php

use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Persistence\EntityManagerInterface;

include __DIR__ . '/../../vendor/autoload.php';
include __DIR__ . '/../Configuration/Essentials/Secrets.php';
include __DIR__ . '/../Configuration/Cache.php';
include __DIR__ . '/../Configuration/Persistence.php';

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet(ObjectManager::get(EntityManagerInterface::class));
