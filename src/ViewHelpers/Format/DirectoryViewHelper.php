<?php

namespace Bleicker\Cms\ViewHelpers\Format;

use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class Directory
 *
 * @package Bleicker\Cms\ViewHelpers\Format
 */
class DirectoryViewHelper extends AbstractViewHelper {

	/**
	 * Initialize the arguments.
	 *
	 * @return void
	 */
	public function initializeArguments() {
		$this->registerArgument('subject', 'string', 'The name of children variable', FALSE);
	}

	/**
	 * @return string
	 */
	public function render() {
		$subject = $this->arguments['subject'];
		if ($subject === NULL) {
			$subject = $this->renderChildren();
		}
		return str_replace('\\', DIRECTORY_SEPARATOR, $subject);
	}
}
