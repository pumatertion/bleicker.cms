<?php

namespace Bleicker\Cms\Controller;

use Bleicker\Framework\Controller\AbstractController;
use Bleicker\Framework\Security\Vote\Exception\ControllerInvokationExceptionInterface;

/**
 * Class LoginController
 *
 * @package Bleicker\Cms\Controller
 */
class LoginController extends AbstractController {

	/**
	 * @param string $originControllerName
	 * @param string $originMethodName
	 * @param ControllerInvokationExceptionInterface $invokedException
	 * @return string
	 */
	public function indexAction($originControllerName = NULL, $originMethodName = NULL, ControllerInvokationExceptionInterface $invokedException = NULL) {
		if ($invokedException !== NULL) {
			$this->view->assign('interceptedUri', $this->request->getParentRequest()->getRequestUri());
		}
		return $this->view->assign('exception', $invokedException)->render();
	}

	/**
	 * @return void
	 */
	public function authenticateAction() {
		$interceptedUri = $this->request->getContent('interceptedUri');
		if ($interceptedUri !== '') {
			$this->redirect($interceptedUri, 303, 'Redirect to Intercepted Request', FALSE);
		}
		$this->redirect('/authenticate');
	}
}
