<?php

namespace Bleicker\Cms\ViewHelpers;

use Bleicker\Registry\Registry;
use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class RegisteredNodeTypesViewHelper
 *
 * @package Bleicker\Cms\ViewHelpers
 */
class RegisteredNodeTypesViewHelper extends AbstractViewHelper {

	/**
	 * @return array
	 */
	public function render() {
		$registeredNodeTypes = Registry::get('nodetypes');
		return (array)$registeredNodeTypes;
	}
}
