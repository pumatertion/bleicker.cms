<?php

namespace Bleicker\Cms\Http;

use Bleicker\Authentication\AuthenticationManager;
use Bleicker\Authentication\AuthenticationManagerInterface;
use Bleicker\Framework\Http\Handler as HandlerOrigin;
use Bleicker\ObjectManager\ObjectManager;

/**
 * Class Handler
 *
 * @package Bleicker\Cms\Http
 */
class Handler extends HandlerOrigin {

	/**
	 * @var AuthenticationManagerInterface
	 */
	protected $authentictionManager;

	/**
	 * @return $this
	 */
	public function initialize() {
		parent::initialize();
		$this->authentictionManager = ObjectManager::get(AuthenticationManagerInterface::class, function () {
			$authenticationManager = new AuthenticationManager();
			ObjectManager::add(AuthenticationManagerInterface::class, $authenticationManager, TRUE);
			return $authenticationManager;
		});
		return $this;
	}

	/**
	 * @return $this
	 */
	public function handle() {
		$this->authentictionManager->run();
		return parent::handle();
	}
}
