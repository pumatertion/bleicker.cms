<?php

namespace Tests\Bleicker\Cms\Unit;

use Bleicker\Cms\Modules\ModuleConfigurationInterface;
use Bleicker\Cms\Modules\ModuleConfigurations;
use Tests\Bleicker\Cms\Unit\Fixtures\ExampleModule;
use Tests\Bleicker\Cms\UnitTestCase;

/**
 * Class ModuleTest
 *
 * @package Tests\Bleicker\Cms\Unit
 */
class ModuleTest extends UnitTestCase {

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
	public function moduleRegistrationTest() {
		ExampleModule::register('label', 'description', ExampleModule::MANAGEMENT_GROUP, '/foo');
		$this->assertTrue(ModuleConfigurations::has(ExampleModule::class));
		/** @var ModuleConfigurationInterface $exampleModuleConfiguration */
		$exampleModuleConfiguration = ModuleConfigurations::get(ExampleModule::class);
		$this->assertEquals('label', $exampleModuleConfiguration->getLabel());
		$this->assertEquals('description', $exampleModuleConfiguration->getDescription());
		$this->assertEquals(ExampleModule::MANAGEMENT_GROUP, $exampleModuleConfiguration->getGroup());
		$this->assertEquals('/foo', $exampleModuleConfiguration->getUri());
	}

	/**
	 * @test
	 */
	public function allowsRoleTest() {
		ExampleModule::register('label', 'description', ExampleModule::MANAGEMENT_GROUP, '/foo')->addAllowedRoleName('foo')->addAllowedRoleName('bar')->addAllowedRoleName('baz')->removeAllowedRoleName('baz');
		$this->assertTrue(ModuleConfigurations::has(ExampleModule::class));
		/** @var ModuleConfigurationInterface $exampleModuleConfiguration */
		$exampleModuleConfiguration = ModuleConfigurations::get(ExampleModule::class);
		$this->assertTrue($exampleModuleConfiguration->allowsRoleName('foo'),'allows foo');
		$this->assertTrue($exampleModuleConfiguration->allowsRoleName('bar'),'allows bar');
		$this->assertFalse($exampleModuleConfiguration->allowsRoleName('baz'), 'forbids baz');
	}

}
