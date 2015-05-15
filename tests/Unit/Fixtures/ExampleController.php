<?php

namespace Tests\Bleicker\Cms\Unit\Fixtures;

use Bleicker\Framework\Controller\AbstractController;

/**
 * Class ExampleController
 *
 * @package Tests\Bleicker\Cms\Unit\Fixtures
 */
class ExampleController extends AbstractController {

	/**
	 * @return string
	 */
	public function fooAction() {
		return 'foo';
	}
}
