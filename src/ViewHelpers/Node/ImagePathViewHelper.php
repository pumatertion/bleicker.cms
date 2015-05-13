<?php

namespace Bleicker\Cms\ViewHelpers\Node;

use Bleicker\Registry\Registry;
use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class ImagePathViewHelper
 *
 * @package Bleicker\Cms\ViewHelpers\Node
 */
class ImagePathViewHelper extends AbstractViewHelper {

	/**
	 * @var boolean
	 */
	protected $escapeChildren = FALSE;

	/**
	 * Initialize the arguments.
	 *
	 * @return void
	 * @api
	 */
	public function initializeArguments() {
		$this->registerArgument('path', 'mixed', 'The absolute system path to an image of a node', TRUE);
	}

	/**
	 * @return array
	 */
	public function render() {
		$path = $this->arguments['path'];
		if ($path === NULL) {
			$path = $this->renderChildren();
		}
		$searchPattern = realpath(Registry::get('paths.uploads.default'));
		$replacePattern = '/Uploads';
		$webPath = str_ireplace($searchPattern, $replacePattern, $path);
		return $webPath;
	}
}
