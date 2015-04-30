<?php
namespace Tests\Bleicker\Cms;

use Bleicker\Nodes\AbstractNode;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Persistence\EntityManagerInterface;
use Tests\Bleicker\Nodes\Functional\Fixtures\Content;
use Tests\Bleicker\Nodes\Functional\Fixtures\Page;

/**
 * Class FunctionalTestCase
 *
 * @package Tests\Bleicker\Cms
 */
abstract class FunctionalTestCase extends BaseTestCase {

	/**
	 * @var EntityManagerInterface
	 */
	protected $entityManager;

	protected function setUp() {
		parent::setUp();
		$this->initDB();
	}

	protected function initDB() {
		include_once __DIR__ . '/Functional/Configuration/Secrets.php';
		include_once __DIR__ . '/Functional/Configuration/Persistence.php';

		/** @var EntityManagerInterface $em */
		$this->entityManager = ObjectManager::get(EntityManagerInterface::class);
	}
}
