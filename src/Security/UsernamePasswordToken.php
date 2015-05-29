<?php

namespace Bleicker\Cms\Security;

use Bleicker\Account\Account;
use Bleicker\Account\AccountInterface;
use Bleicker\Account\Credential;
use Bleicker\Encryption\Bcrypt;
use Bleicker\Framework\HttpApplicationRequestInterface;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Persistence\EntityManagerInterface;
use Bleicker\Token\AbstractSessionToken;
use Doctrine\DBAL\DBALException;

/**
 * Class UsernamePasswordToken
 *
 * @package Bleicker\Cms\Security
 */
class UsernamePasswordToken extends AbstractSessionToken {

	const USERNAME_PATH = 'bleicker.cms.security.username_password_token.username', PASSWORD_PATH = 'bleicker.cms.security.username_password_token.password';

	/**
	 * @var HttpApplicationRequestInterface
	 */
	protected $request;

	/**
	 * @var EntityManagerInterface
	 */
	protected $entityManager;

	/**
	 * @return $this
	 */
	protected function initialize() {
		$this->entityManager = ObjectManager::get(EntityManagerInterface::class);
		$this->request = ObjectManager::get(HttpApplicationRequestInterface::class);
		return parent::initialize();
	}

	/**
	 * @return AccountInterface
	 */
	public function reconstituteAccountFromSession() {
		try {
			$this->request->getParentRequest()->getSession()->start();
			$accountIdentity = $this->request->getParentRequest()->getSession()->get($this->getSessionKey());
			if ($accountIdentity !== NULL) {
				$queryBuilder = $this->entityManager->createQueryBuilder();
				$accounts = $queryBuilder->select('a')->from(Account::class, 'a')->where('a.identity = :identity')
					->setParameter('identity', $accountIdentity)
					->getQuery()->execute();
				if (count($accounts) === 1) {
					return $accounts[0];
				}
			}
		} catch (DBALException $exception) {
			return NULL;
		}
		return NULL;
	}

	/**
	 * @return $this
	 */
	public function injectCredential() {
		$this->getCredential()->setValue($this->request->getContent(self::PASSWORD_PATH));
	}

	/**
	 * @return $this
	 */
	public function fetchAndSetAccount() {
		try {
			$identity = $this->request->getContent(self::USERNAME_PATH);
			$queryBuilder = $this->entityManager->createQueryBuilder();
			$credentials = $queryBuilder->select('c')->from(Credential::class, 'c')->leftJoin('c.account', 'a')->where('a.identity = :identity')
				->setParameter('identity', $identity)
				->getQuery()->execute();
		} catch (DBALException $exception) {
			return $this;
		}

		/** @var Credential $credential */
		foreach ($credentials as $credential) {
			if (Bcrypt::validate($this->credential->getValue(), $credential->getValue())) {
				$this->credential->setAccount($credential->getAccount());
				$this->request->getParentRequest()->getSession()->start();
				$this->request->getParentRequest()->getSession()->set($this->getSessionKey(), $credential->getAccount()->getIdentity());
			}
		}

		return $this;
	}

	/**
	 * @return void
	 */
	public function clearSession() {
		$this->request->getParentRequest()->getSession()->start();
		$this->request->getParentRequest()->getSession()->remove($this->getSessionKey());
	}
}
