<?php

namespace Bleicker\Cms\Security\Exception;

use Bleicker\Cms\Controller\AuthenticationController;
use Bleicker\Framework\Security\Vote\Exception\AbstractControllerInvocationException;

/**
 * Class LoginBoxException
 *
 * @package Bleicker\Cms\Security\Exception
 */
class LoginBoxException extends AbstractControllerInvocationException {

	const CONTROLLER_NAME = AuthenticationController::class, METHOD_NAME = 'indexAction';
}
