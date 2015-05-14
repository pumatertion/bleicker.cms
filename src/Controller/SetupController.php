<?php

namespace Bleicker\Cms\Controller;

use Bleicker\Framework\Controller\AbstractController;
use Bleicker\Registry\Registry;

/**
 * Class SetupController
 *
 * @package Bleicker\Cms\Controller
 */
class SetupController extends AbstractController {

	const TOKEN_FILENAME = 'setup.token';

	/**
	 * @var string
	 */
	protected $tokenFile;

	public function __construct() {
		parent::__construct();
		$this->tokenFile = Registry::get('paths.tokens.default') . self::TOKEN_FILENAME;
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
	 * @return string
	 */
	public function authenticationAction() {
		return $this->view->render();
	}

	/**
	 * @return string
	 */
	public function authenticateAction() {
		$this->redirect('/setup');
	}

	/**
	 * @return string
	 */
	public function setupAction() {
		return $this->view->render();
	}
}
