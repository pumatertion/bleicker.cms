<?php

namespace Bleicker\Cms\Security\Exception;

use Bleicker\Cms\Controller\LoginController;
use Bleicker\Framework\Security\Vote\Exception\AbstractControllerInvokationException;

/**
 * Class LoginBoxException
 *
 * @package Bleicker\Cms\Security\Exception
 */
class LoginBoxException extends AbstractControllerInvokationException {

	const CONTROLLER_NAME = LoginController::class, METHOD_NAME = 'indexAction';
}
