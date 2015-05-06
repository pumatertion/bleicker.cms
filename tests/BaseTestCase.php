<?php
namespace Tests\Bleicker\Cms;

/**
 * Class BaseTestCase
 *
 * @package Tests\Bleicker\Cms
 */
abstract class BaseTestCase extends \PHPUnit_Framework_TestCase {

	protected function setUp() {
		putenv('CONTEXT=testing');
	}
}
