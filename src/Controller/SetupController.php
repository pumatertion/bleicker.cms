<?php

namespace Bleicker\Cms\Controller;

use Bleicker\Account\Account;
use Bleicker\Account\Credential;
use Bleicker\Account\Role;
use Bleicker\Cms\Modules\ModuleInterface;
use Bleicker\Cms\Modules\ModuleTrait;
use Bleicker\Cms\Security\SetupToken;
use Bleicker\Encryption\Bcrypt;
use Bleicker\Framework\Controller\AbstractController;
use Bleicker\Framework\Security\Vote\Exception\ControllerInvokationExceptionInterface;
use Bleicker\Nodes\NodeServiceInterface;
use Bleicker\NodeTypes\Site;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Persistence\EntityManagerInterface;
use Bleicker\Registry\Registry;
use Doctrine\Common\Collections\Criteria;

/**
 * Class SetupController
 *
 * @package Bleicker\Cms\Controller
 */
class SetupController extends AbstractController implements ModuleInterface {

	use ModuleTrait;

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
		file_put_contents($this->tokenFile, Bcrypt::encrypt(SetupToken::PASSWORD_PREFIX . $password));
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
		$currentSettings = Registry::get('doctrine.db.default');
		return $this->view->assign('current', $currentSettings)->render();
	}

	/**
	 * @return string
	 * @todo validate url
	 */
	public function createDatabaseAction() {
		$path = Registry::get('paths.root') . '/src/Configuration/Includes/Secrets.local.php';
		$content = <<<CONTENT
<?php

use Bleicker\Registry\Registry;

Registry::set('doctrine.db.default.driver', '%1\$s');
Registry::set('doctrine.db.default.host', '%2\$s');
Registry::set('doctrine.db.default.port', '%3\$s');
Registry::set('doctrine.db.default.dbname', '%4\$s');
Registry::set('doctrine.db.default.path', '%5\$s');
Registry::set('doctrine.db.default.user', '%6\$s');
Registry::set('doctrine.db.default.password', '%7\$s');
Registry::set('doctrine.db.default.charset', '%8\$s');

CONTENT;

		if (is_file($path)) {
			unlink($path);
		}

		file_put_contents($path, sprintf($content,
			$this->request->getContent('driver'),
			$this->request->getContent('host'),
			$this->request->getContent('port'),
			$this->request->getContent('dbname'),
			$this->request->getContent('path'),
			$this->request->getContent('user'),
			$this->request->getContent('password'),
			$this->request->getContent('charset')
		));

		$this->redirect('/setup/schema', 307);
	}

	/**
	 * @return void
	 */
	public function createSchemaAction() {
		$tool = new \Doctrine\ORM\Tools\SchemaTool($this->entityManager);
		$tool->updateSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
		$this->createFirstSite();
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

		/** @var EntityManagerInterface $entityManager */
		$entityManager = ObjectManager::get(EntityManagerInterface::class);

		$expr = Criteria::expr();
		$criteria = Criteria::create();
		$criteria->where(
			$expr->eq('name', 'Administrator')
		);

		$administratorRole = $entityManager->getRepository(Role::class)->matching($criteria)->first();

		if (!$administratorRole) {
			$administratorRole = new Role('Administrator');
		}

		$account = new Account();
		$account
			->setIdentity($this->request->getContent('username'))
			->addRole($administratorRole);

		$credentialValue = Bcrypt::encrypt($this->request->getContent('password'));
		$credential = new Credential($credentialValue, $account);

		$this->entityManager->persist($credential);
		$this->entityManager->flush();

		$this->redirect('/nodemanager');
	}

	/**
	 * @return void
	 */
	protected function createFirstSite() {
		/** @var NodeServiceInterface $nodeService */
		$nodeService = ObjectManager::get(NodeServiceInterface::class);
		if ((integer)$nodeService->findSites()->count() === 0) {
			$site = new Site();
			$site->setTitle('www.foo.com');
			$nodeService->add($site);
		}
	}
}
