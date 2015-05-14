<?php

namespace Bleicker\Cms\Security\Exception;

use Bleicker\Cms\Controller\AuthenticationController;
use Bleicker\Cms\Controller\SetupController;
use Bleicker\Framework\Security\Vote\Exception\AbstractControllerInvokationException;

/**
 * Class SetupLoginBoxException
 *
 * @package Bleicker\Cms\Security\Exception
 */
class SetupLoginBoxException extends AbstractControllerInvokationException {

	const CONTROLLER_NAME = SetupController::class, METHOD_NAME = 'authenticationAction';
}
