<?php

namespace Bleicker\Cms\Security;

use Bleicker\Account\Account;
use Bleicker\Account\AccountInterface;
use Bleicker\Account\Role;
use Bleicker\Framework\HttpApplicationRequestInterface;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Registry\Registry;
use Bleicker\Token\AbstractSessionToken;

/**
 * Class SetupToken
 *
 * @package Bleicker\Cms\Security
 */
class SetupToken extends AbstractSessionToken {

	const PASSWORD = 'password', TOKEN_FILENAME = 'setup.token';

	/**
	 * @var HttpApplicationRequestInterface
	 */
	protected $request;

	/**
	 * @return $this
	 */
	protected function initialize() {
		$this->request = ObjectManager::get(HttpApplicationRequestInterface::class);
		return parent::initialize();
	}

	/**
	 * @return string
	 */
	public static function getTokenFilePath() {
		return Registry::get('paths.tokens.default') . '/' . self::TOKEN_FILENAME;
	}

	/**
	 * @return AccountInterface
	 */
	public function reconstituteAccountFromSession() {
		$this->request->getParentRequest()->getSession()->start();
		$account = $this->request->getParentRequest()->getSession()->get($this->getSessionKey());
		if ($account !== TRUE) {
			return NULL;
		}
		$role = new Role('Administrator');
		$account = new Account();
		$account->addRole($role);
		return $account;
	}

	/**
	 * @return $this
	 */
	public function injectCredential() {
		$this->getCredential()->setValue($this->request->getContent(self::PASSWORD));
	}

	/**
	 * @return $this
	 */
	public function fetchAndSetAccount() {
		$tokenPassword = file_get_contents(self::getTokenFilePath());
		if ($tokenPassword == $this->credential->getValue()) {
			return $this;
		}
		$role = new Role('Administrator');
		$account = new Account();
		$account->addRole($role);

		$this->request->getParentRequest()->getSession()->start();
		$this->request->getParentRequest()->getSession()->set($this->getSessionKey(), TRUE);
	}

	/**
	 * @return void
	 */
	public function clearSession() {
		$this->request->getParentRequest()->getSession()->start();
		$this->request->getParentRequest()->getSession()->remove($this->getSessionKey());
	}
}
