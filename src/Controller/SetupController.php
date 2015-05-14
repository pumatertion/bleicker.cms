<?php

namespace Bleicker\Cms\Controller;

use Bleicker\Account\Account;
use Bleicker\Account\Credential;
use Bleicker\Account\Role;
use Bleicker\Cms\Security\SetupToken;
use Bleicker\Framework\Controller\AbstractController;
use Bleicker\Framework\Security\Vote\Exception\ControllerInvokationExceptionInterface;
use Bleicker\Registry\Registry;

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
		$this->redirect('/setup/token');
	}

	/**
	 * @return string
	 */
	public function setupAction() {
		$this->redirect('/setup/database');
	}

	/**
	 * @return string
	 */
	public function setupDatabaseAction() {
		return $this->view->render();
	}

	/**
	 * @return string
	 * @todo validate url argument
	 */
	public function createDatabaseAction() {
		$path = Registry::get('paths.root') . '/src/Configuration/Includes/Secrets.local.php';
		$content = <<<CONTENT
<?php

use Bleicker\Registry\Registry;

Registry::set('doctrine.db.default.url', '%1\$s');
CONTENT;

		if (is_file($path)) {
			unlink($path);
		}

		file_put_contents($path, sprintf($content, $this->request->getContent('url')));

		$this->redirect('/setup/schema', 307);
	}

	/**
	 * @return void
	 */
	public function createSchemaAction() {
		$tool = new \Doctrine\ORM\Tools\SchemaTool($this->entityManager);
		$tool->createSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
		$this->redirect('/setup/admin');
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
	public function createAdministratorAction() {
		$account = new Account();
		$account
			->setIdentity($this->request->getContent('username'))
			->addRole(new Role('Administrator'));

		$credentialValue = $this->request->getContent('password');
		$credential = new Credential($credentialValue, $account);

		$this->entityManager->persist($credential);
		$this->entityManager->flush();

		$this->redirect('/nodemanager');
	}
}
