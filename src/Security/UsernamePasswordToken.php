<?php

namespace Bleicker\Cms\Security;

use Bleicker\Account\Account;
use Bleicker\Account\AccountInterface;
use Bleicker\Account\Credential;
use Bleicker\Framework\HttpApplicationRequestInterface;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Persistence\EntityManagerInterface;
use Bleicker\Token\AbstractSessionToken;

/**
 * Class UsernamePasswordToken
 *
 * @package Bleicker\Cms\Security
 */
class UsernamePasswordToken extends AbstractSessionToken {

	const USERNAME = 'username', PASSWORD = 'password';

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
		$this->request->getParentRequest()->getSession()->start();
		$accountId = $this->request->getParentRequest()->getSession()->get($this->getSessionKey());
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
			return $this;
		}

		/** @var Account $account */
		$account = $accounts[0];

		$this->request->getParentRequest()->getSession()->start();
		$this->request->getParentRequest()->getSession()->set($this->getSessionKey(), $account->getId());
	}

	/**
	 * @return void
	 */
	public function clearSession() {
		$this->request->getParentRequest()->getSession()->start();
		$this->request->getParentRequest()->getSession()->remove($this->getSessionKey());
	}
}
