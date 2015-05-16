<?php

namespace Bleicker\Cms\ViewHelpers\Format;

use Bleicker\Cms\Utility\Parser;
use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class TextParser
 *
 * @package Bleicker\Cms\ViewHelpers\Format
 */
class NodeTagParserViewHelper extends AbstractViewHelper {

	/**
	 * Initialize the arguments.
	 *
	 * @return void
	 */
	public function initializeArguments() {
		$this->registerArgument('subject', 'string', 'Text to parse', FALSE);
		$this->registerArgument('prefix', 'string', 'Uri prefix', FALSE);
	}

	/**
	 * @return string
	 */
	public function render() {
		$subject = $this->arguments['subject'];
		$prefix = $this->hasArgument('prefix') ? $this->arguments['subject'] : NULL;
		if ($subject === NULL) {
			$subject = $this->renderChildren();
		}
		return Parser::nodeTagIdToPath($subject, Parser::defaultNodeTagToAnchorClosure($prefix));
	}
}
