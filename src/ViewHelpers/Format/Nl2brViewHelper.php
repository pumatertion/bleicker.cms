<?php

namespace Bleicker\Cms\ViewHelpers\Format;

use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class Nl2brViewHelper
 *
 * @package Bleicker\Cms\ViewHelpers\Format
 */
class Nl2brViewHelper extends AbstractViewHelper {

	/**
	 * @var boolean
	 */
	protected $escapeOutput = FALSE;

	/**
	 * @return string
	 */
	public function render() {
		return nl2br(trim($this->renderChildren()));
	}
}
