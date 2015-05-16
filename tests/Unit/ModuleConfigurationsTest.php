<?php

namespace Tests\Bleicker\Cms\Unit;

use Bleicker\Account\Role;
use Bleicker\Cms\Modules\ModuleConfigurations;
use Doctrine\Common\Collections\ArrayCollection;
use Tests\Bleicker\Cms\Unit\Fixtures\AnotherExampleModule;
use Tests\Bleicker\Cms\Unit\Fixtures\ExampleModule;
use Tests\Bleicker\Cms\UnitTestCase;

/**
 * Class ModuleConfigurationsTest
 *
 * @package Tests\Bleicker\Cms\Unit
 */
class ModuleConfigurationsTest extends UnitTestCase {

	protected function setUp() {
		parent::setUp();
		ModuleConfigurations::prune();
	}

	protected function tearDown() {
		parent::tearDown();
		ModuleConfigurations::prune();
	}

	/**
	 * @test
	 */
	public function getAllowedByRolesTest() {
		ExampleModule::register('label', 'description', ExampleModule::MANAGEMENT_GROUP, '/foo')->addAllowedRoleName('foo')->addAllowedRoleName('bar');
		AnotherExampleModule::register('label', 'description', ExampleModule::MANAGEMENT_GROUP, '/foo')->addAllowedRoleName('foo')->addAllowedRoleName('baz');

		$foo = new Role('foo');
		$bar = new Role('bar');
		$baz = new Role('baz');
		$notUsed = new Role('Guest');

		$roles = new ArrayCollection([$foo]);
		$this->assertEquals(2, ModuleConfigurations::getAllowedByRoles($roles)->count());

		$roles = new ArrayCollection([$foo, $bar]);
		$this->assertEquals(2, ModuleConfigurations::getAllowedByRoles($roles)->count());

		$roles = new ArrayCollection([$foo, $baz]);
		$this->assertEquals(2, ModuleConfigurations::getAllowedByRoles($roles)->count());

		$roles = new ArrayCollection([$foo, $bar, $baz]);
		$this->assertEquals(2, ModuleConfigurations::getAllowedByRoles($roles)->count());

		$roles = new ArrayCollection([$notUsed]);
		$this->assertEquals(0, ModuleConfigurations::getAllowedByRoles($roles)->count());
	}
}
