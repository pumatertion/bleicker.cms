<?php

namespace Bleicker\Cms\Security;

use Bleicker\Account\Account;
use Bleicker\Account\AccountInterface;
use Bleicker\Account\Credential;
use Bleicker\Framework\ApplicationRequestInterface;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Persistence\EntityManagerInterface;
use Bleicker\Token\AbstractSessionToken;

/**
 * Class Token
 *
 * @package Bleicker\Account
 */
class HttpToken extends AbstractSessionToken {

	const USERNAME = 'username', PASSWORD = 'password';

	/**
	 * @var ApplicationRequestInterface
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
		$this->request = ObjectManager::get(ApplicationRequestInterface::class);
		return parent::initialize();
	}

	/**
	 * @return AccountInterface
	 */
	public function reconstituteAccountFromSession() {
		$this->request->getMainRequest()->getSession()->start();
		$accountId = $this->request->getMainRequest()->getSession()->get($this->getSessionKey());
		if ($accountId !== NULL) {
			$queryBuilder = $this->entityManager->createQueryBuilder();
			$accounts = $queryBuilder->select('a')->from(Account::class, 'a')->where('a.id = :id')
				->setParameter('id', $accountId)
				->getQuery()->execute();
			if (count($accounts) === 1) {
				return $accounts[0];
			}
		}
		return NULL;
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
		$identity = $this->request->getContent(self::USERNAME);
		$queryBuilder = $this->entityManager->createQueryBuilder();
		$accounts = $queryBuilder->select('c')->from(Credential::class, 'c')->leftJoin('c.account', 'a')->where('c.value = :value AND a.identity = :identity')
			->setParameter('identity', $identity)
			->setParameter('value', $this->getCredential()->getValue())
			->getQuery()->execute();

		if (count($accounts) !== 1) {
			return FALSE;
		}

		/** @var Account $account */
		$account = $accounts[0];

		$this->request->getMainRequest()->getSession()->start();
		$this->request->getMainRequest()->getSession()->set($this->getSessionKey(), $account->getId());
	}



//
//
//	const USERNAME = 'username', PASSWORD = 'password', SESSION_KEY = 'authenticatedAccount';
//
//	/**
//	 * @var ApplicationRequestInterface
//	 */
//	protected $request;
//
//	/**
//	 * @var EntityManagerInterface
//	 */
//	protected $entityManager;
//
//	/**
//	 * @return $this
//	 */
//	protected function initialize() {
//		$this->entityManager = ObjectManager::get(EntityManagerInterface::class);
//		$this->request = ObjectManager::get(ApplicationRequestInterface::class);
//		$this->entityManager = ObjectManager::get(EntityManagerInterface::class);
//
//		/** @var Request $httpRequest */
//		$httpRequest = $this->request->getMainRequest();
//		$httpRequest->getSession()->start();
//		if ($httpRequest->getSession()->has(self::SESSION_KEY)) {
//			$this->status = self::AUTHENTICATION_SUCCESS;
//			return $this;
//		}
//		return parent::initialize();
//	}
//
//	/**
//	 * @return void
//	 */
//	public function injectCredential() {
//		$contents = $this->request->getContents();
//		$username = Arrays::getValueByPath($contents, self::USERNAME);
//		$password = Arrays::getValueByPath($contents, self::PASSWORD);
//		if ($password !== NULL && $username !== NULL) {
//			$this->credential = array(
//				self::USERNAME => $username,
//				self::PASSWORD => $password
//			);
//		}
//	}
//
//	/**
//	 * @return boolean
//	 */
//	public function isCredentialValid() {
//		$queryBuilder = $this->entityManager->createQueryBuilder();
//		$accounts = $queryBuilder->select('c')->from(Credential::class, 'c')->leftJoin('c.account', 'a')->where('c.password = :password AND a.username = :username')
//			->setParameter('username', $this->getCredential()[self::USERNAME])
//			->setParameter('password', $this->getCredential()[self::PASSWORD])
//			->getQuery()->execute();
//
//		if (count($accounts) !== 1) {
//			return FALSE;
//		}
//
//		/** @var Account $account */
//		$account = $accounts[0];
//
//		$this->request->getMainRequest()->getSession()->start();
//		$this->request->getMainRequest()->getSession()->set(self::SESSION_KEY, $account->getId());
//		return TRUE;
//	}
}
