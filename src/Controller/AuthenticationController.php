<?php

namespace Bleicker\Cms\Controller;

use Bleicker\Authentication\AuthenticationManagerInterface;
use Bleicker\Framework\Controller\AbstractController;
use Bleicker\Framework\Security\Vote\Exception\ControllerInvokationExceptionInterface;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Token\TokenInterface;

/**
 * Class AuthenticationController
 *
 * @package Bleicker\Cms\Controller
 */
class AuthenticationController extends AbstractController {

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
		$this->redirect('/');
	}

	/**
	 * @return void
	 */
	public function logoutAction() {
		/** @var AuthenticationManagerInterface $authenticationManager */
		$authenticationManager = ObjectManager::get(AuthenticationManagerInterface::class);
		$tokens = $authenticationManager->getTokens();
		/** @var TokenInterface $token */
		while($token = $tokens->current()){
			$authenticationManager->logout($token);
			$tokens->next();
		}
		$this->redirect('/');
	}
}
