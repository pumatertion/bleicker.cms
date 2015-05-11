<?php

namespace Bleicker\Cms\Security;

use Bleicker\Account\Account;
use Bleicker\Account\Credential;
use Bleicker\Framework\ApplicationRequestInterface;
use Bleicker\Framework\Utility\Arrays;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Persistence\EntityManagerInterface;
use Bleicker\Token\AbstractToken;
use Bleicker\Framework\Http\Request;

/**
 * Class Token
 *
 * @package Bleicker\Account
 */
class HttpToken extends AbstractToken {

	const USERNAME = 'username', PASSWORD = 'password', SESSION_KEY = 'authenticatedAccount';

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
		$this->entityManager = ObjectManager::get(EntityManagerInterface::class);

		/** @var Request $httpRequest */
		$httpRequest = $this->request->getMainRequest();
		$httpRequest->getSession()->start();
		if($httpRequest->getSession()->has(self::SESSION_KEY)){
			$this->status = self::AUTHENTICATION_SUCCESS;
			return $this;
		}
		return parent::initialize();
	}

	/**
	 * @return void
	 */
	public function injectCredential() {
		$contents = $this->request->getContents();
		$username = Arrays::getValueByPath($contents, self::USERNAME);
		$password = Arrays::getValueByPath($contents, self::PASSWORD);
		if ($password !== NULL && $username !== NULL) {
			$this->credential = array(
				self::USERNAME => $username,
				self::PASSWORD => $password
			);
		}
	}

	/**
	 * @return boolean
	 */
	public function isCredentialValid() {
		$queryBuilder = $this->entityManager->createQueryBuilder();
		$accounts = $queryBuilder->select('c')->from(Credential::class, 'c')->leftJoin('c.account', 'a')->where('c.password = :password AND a.username = :username')
			->setParameter('username', $this->getCredential()[self::USERNAME])
			->setParameter('password', $this->getCredential()[self::PASSWORD])
			->getQuery()->execute();

		if (count($accounts) !== 1) {
			return FALSE;
		}

		/** @var Account $account */
		$account = $accounts[0];

		$this->request->getMainRequest()->getSession()->start();
		$this->request->getMainRequest()->getSession()->set(self::SESSION_KEY, $account->getId());
		return TRUE;
	}
}
