<?php

use Bleicker\Cms\Controller\NodeController;
use Bleicker\Cms\Security\Exception\LoginBoxException;
use Bleicker\Security\Vote;

Vote::register('SecuredController', function () {
	throw new LoginBoxException('Please authenticate to get access.', 1431290564);
}, NodeController::class . '::.*');