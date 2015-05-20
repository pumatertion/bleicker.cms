<?php

namespace Bleicker\Cms\Security\Exception;

use Bleicker\Cms\Controller\SetupController;
use Bleicker\Framework\Security\Vote\Exception\AbstractControllerInvocationException;

/**
 * Class SetupLoginBoxException
 *
 * @package Bleicker\Cms\Security\Exception
 */
class SetupLoginBoxException extends AbstractControllerInvocationException {

	const CONTROLLER_NAME = SetupController::class, METHOD_NAME = 'authenticationAction';
}
