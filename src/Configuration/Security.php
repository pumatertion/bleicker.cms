<?php

use Bleicker\Account\RoleInterface;
use Bleicker\Authentication\AuthenticationManagerInterface;
use Bleicker\Cms\Controller\NodeController;
use Bleicker\Cms\Security\Exception\LoginBoxException;
use Bleicker\Cms\Security\HttpToken;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Security\Vote;

HttpToken::register(HttpToken::class);

Vote::register('SecuredController', function () {
	/** @var AuthenticationManagerInterface $authenticationManager */
	$authenticationManager = ObjectManager::get(AuthenticationManagerInterface::class);
	$isAdmin = (boolean)$authenticationManager->getRoles()->filter(function (RoleInterface $role) {
		return $role->getName() === 'Administrator';
	})->count();
	if (!$isAdmin) {
		throw new LoginBoxException('Administrator privilege required.', 1431290565);
	}
}, NodeController::class . '::.*');