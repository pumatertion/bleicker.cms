<?php

namespace Bleicker\Cms\Controller;

use Bleicker\Cms\Security\SetupToken;
use Bleicker\Framework\Controller\AbstractController;
use Bleicker\Framework\Security\Vote\Exception\ControllerInvokationExceptionInterface;

/**
 * Class SetupController
 *
 * @package Bleicker\Cms\Controller
 */
class SetupController extends AbstractController {

	/**
	 * @var string
	 */
	protected $tokenFile;

	public function __construct() {
		parent::__construct();
		$this->tokenFile = SetupToken::getTokenFilePath();
	}

	/**
	 * @return string
	 */
	public function newTokenAction() {
		if (!file_exists($this->tokenFile)) {
			return $this->view->render();
		}
		$this->redirect('/setup/authenticate');
	}

	/**
	 * @return void
	 */
	public function createTokenAction() {
		$password = $this->request->getContent('password');
		file_put_contents($this->tokenFile, $password);
		$this->redirect('/setup/authentication');
	}

	/**
	 * @param string $originControllerName
	 * @param string $originMethodName
	 * @param ControllerInvokationExceptionInterface $invokedException
	 * @return string
	 */
	public function authenticationAction($originControllerName = NULL, $originMethodName = NULL, ControllerInvokationExceptionInterface $invokedException = NULL) {
		if (file_exists($this->tokenFile)) {
			return $this->view->render();
		}
		$this->redirect('/setup/new-token');
	}

	/**
	 * @return string
	 */
	public function setupAction() {
		$this->redirect('/setup-database');
	}

	/**
	 * @return string
	 */
	public function setupDatabaseAction() {
		return $this->view->render();
	}

	/**
	 * @return string
	 */
	public function createDatabaseAction(){
		$this->redirect('/setup-database');
	}

	/**
	 * @return string
	 */
	public function setupAdministratorAction() {
		return $this->view->render();
	}

	/**
	 * @return string
	 */
	public function createAdministratorAction(){
		$this->redirect('/nodemanager');
	}
}
