<?php

use Bleicker\Authentication\AuthenticationManagerInterface;
use Bleicker\Cms\Controller\NodeController;
use Bleicker\Cms\Security\Exception\LoginBoxException;
use Bleicker\Cms\Security\HttpToken;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Security\Vote;

HttpToken::register(HttpToken::class);

Vote::register('SecuredController', function () {
	if (!ObjectManager::get(AuthenticationManagerInterface::class)->hasRole('Administrator')) {
		throw new LoginBoxException('Administrator privilege required.', 1431290565);
	}
}, NodeController::class . '::.*');