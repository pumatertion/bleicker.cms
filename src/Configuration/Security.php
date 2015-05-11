<?php

use Bleicker\Cms\Controller\NodeController;
use Bleicker\Cms\Security\Exception\LoginBoxException;
use Bleicker\Cms\Security\HttpToken;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Security\Vote;
use Bleicker\Session\SessionInterface;

HttpToken::register(HttpToken::class);

Vote::register('SecuredController', function () {
	/** @var SessionInterface $session */
	$session = ObjectManager::get(SessionInterface::class);
	$session->start();
	if ($session->get(HttpToken::SESSION_KEY) === NULL) {
		throw new LoginBoxException('Authentication failed.', 1431290565);
	}
}, NodeController::class . '::.*');