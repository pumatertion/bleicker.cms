<?php

namespace Unit;

use Bleicker\Authentication\AuthenticationManagerInterface;
use Bleicker\Cms\Security\SetupToken;
use Bleicker\Context\Context;
use Bleicker\Converter\Converter;
use Bleicker\Encryption\Bcrypt;
use Bleicker\Framework\ApplicationFactory;
use Bleicker\Framework\Http\Session;
use Bleicker\Framework\Http\SessionInterface;
use Bleicker\Nodes\Locale;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Persistence\EntityManagerInterface;
use Bleicker\Registry\Registry;
use Bleicker\Registry\Utility\Arrays;
use Bleicker\Routing\ControllerRouteData;
use Bleicker\Routing\RouterInterface;
use Bleicker\Security\Vote;
use Bleicker\Security\Votes;
use Bleicker\Token\Tokens;
use Bleicker\Translation\Locales;
use Closure;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Tests\Bleicker\Cms\Unit\Fixtures\AccessDeniedException;
use Tests\Bleicker\Cms\Unit\Fixtures\EntityManager;
use Tests\Bleicker\Cms\Unit\Fixtures\ExampleController;
use Tests\Bleicker\Cms\UnitTestCase;

/**
 * Class SetupTokenTest
 *
 * @package Unit
 */
class SetupTokenTest extends UnitTestCase {

	protected function setUp() {
		parent::setUp();
		ObjectManager::prune();
		Locales::prune();
		Votes::prune();
		Converter::prune();
		Tokens::prune();
	}

	protected function tearDown() {
		parent::tearDown();
		ObjectManager::prune();
		Locales::prune();
		Votes::prune();
		Converter::prune();
		Tokens::prune();
		Context::prune();
	}

	/**
	 * @return Closure
	 */
	protected function applicationBefore() {
		return function () {
			ObjectManager::add(EntityManagerInterface::class, new EntityManager());
			ObjectManager::add(SessionInterface::class, new Session(new MockArraySessionStorage()));
			Registry::set('paths.tokens.default', __DIR__ . '/Fixtures');
		};
	}

	/**
	 * @return Closure
	 */
	protected function applicationAfter() {
		return function () {
			Locale::register('foo', 'foo', 'FOO');
			SetupToken::register();
			Vote::register('restricted', function () {
				if (!ObjectManager::get(AuthenticationManagerInterface::class)->hasRole('Setup.Administrator')) {
					throw new AccessDeniedException('Administrator privilege required.', 1431290567);
				}
			}, ExampleController::class . '::.*');
		};
	}

	/**
	 * @test
	 * @expectedException \Tests\Bleicker\Cms\Unit\Fixtures\AccessDeniedException
	 */
	public function failedTest() {
		Arrays::setValueByPath($_SERVER, 'PATH_INFO', '/failed');
		Arrays::setValueByPath($_SERVER, 'REQUEST_METHOD', 'POST');
		Arrays::setValueByPath($_POST, 'password', 'wrong');

		$application = ApplicationFactory::http(
			$this->applicationBefore(),
			$this->applicationAfter()
		);

		/** @var RouterInterface $router */
		$router = ObjectManager::get(RouterInterface::class);
		$router->addRoute(ExampleController::class, 'fooAction', '/failed', 'post');

		$application->run();
	}

	/**
	 * @test
	 */
	public function successTest() {
		Arrays::setValueByPath($_SERVER, 'PATH_INFO', '/success');
		Arrays::setValueByPath($_SERVER, 'REQUEST_METHOD', 'POST');
		Arrays::setValueByPath($_POST, 'password', 'right');

		$application = ApplicationFactory::http(
			$this->applicationBefore(),
			$this->applicationAfter()
		);

		/** @var RouterInterface $router */
		$router = ObjectManager::get(RouterInterface::class);
		$router->addRoute(ExampleController::class, 'fooAction', '/success', 'post');

		ob_start();
		$application->run();
		$this->assertEquals('foo', ob_get_contents());
		ob_end_clean();
	}
}
