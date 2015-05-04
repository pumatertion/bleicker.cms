<?php

namespace Bleicker\Cms\ViewHelpers;

use Bleicker\Framework\Utility\Arrays;
use Bleicker\NodeTypes\SiteInterface;
use Bleicker\Registry\Registry;
use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class RegisteredNodeTypesViewHelper
 *
 * @package Bleicker\Cms\ViewHelpers
 */
class RegisteredNodeTypesViewHelper extends AbstractViewHelper {

	/**
	 * Returns all known type excluding sites
	 *
	 * @return array
	 */
	public function render() {
		$registeredNodeTypes = Registry::get('nodetypes');
		foreach ($registeredNodeTypes as $index => $registeredNodeType) {
			$className = Arrays::getValueByPath($registeredNodeType, 'class');
			if ($className === NULL || !class_exists($className)) {
				unset($registeredNodeTypes[$index]);
				continue;
			}
			$reflection = new \ReflectionClass($className);
			if ($reflection->implementsInterface(SiteInterface::class)) {
				unset($registeredNodeTypes[$index]);
				continue;
			}
		}
		return (array)$registeredNodeTypes;
	}
}
