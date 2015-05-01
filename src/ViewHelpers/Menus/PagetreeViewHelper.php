<?php

namespace Bleicker\Cms\ViewHelpers\Menus;

use Bleicker\Nodes\NodeInterface;
use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class PagetreeViewHelper
 *
 * @package Bleicker\Cms\ViewHelpers\Menus
 */
class PagetreeViewHelper extends AbstractViewHelper {

	/**
	 * @var boolean
	 */
	protected $escapeOutput = FALSE;

	/**
	 * Validate arguments, and throw exception if arguments do not validate.
	 *
	 * @return void
	 * @throws \InvalidArgumentException
	 */
	public function __validateArguments() {
		$argumentDefinitions = $this->prepareArguments();
		foreach ($argumentDefinitions as $argumentName => $registeredArgument) {
			if ($this->hasArgument($argumentName)) {
				$value = $this->arguments[$argumentName];
				$type = $registeredArgument->getType();
				if ($value !== $registeredArgument->getDefaultValue() && $type !== 'mixed') {
					$errorException = $this->createAnInvalidArgumentException($argumentName, $type, $value);
					if ($type === 'array' && !is_array($value)) {
						if (!$value instanceof \ArrayAccess && !$value instanceof \Traversable) {
							throw $errorException;
						}
					} elseif ($type === 'string') {
						if (is_object($value) && !method_exists($value, '__toString')) {
							throw $errorException;
						}
					} elseif ($type === 'boolean' && !is_bool($value)) {
						throw $errorException;
					} elseif (is_object($value) && !($value instanceof $type)) {
						throw $errorException;
					}
				}
			}
		}
	}

	/**
	 * @param string $argumentName
	 * @param string $type
	 * @return \InvalidArgumentException
	 */
	private function __createAnInvalidArgumentException($argumentName, $type) {
		$value = $this->arguments[$argumentName];
		$givenType = is_object($value) ? get_class($value) : gettype($value);
		return new \InvalidArgumentException(
			'The argument "' . $argumentName . '" was registered with type "' . $type . '", but is of type "' .
			$givenType . '" in view helper "' . get_class($this) . '".',
			1430483390
		);
	}

	/**
	 * Initialize the arguments.
	 *
	 * @return void
	 * @api
	 */
	public function initializeArguments() {
		$this->registerArgument('of', 'mixed', 'The start page', TRUE);
		$this->registerArgument('as', 'array', 'The name of children variable', FALSE, 'children');
	}

	/**
	 * @return string
	 */
	public function render() {
		/** @var NodeInterface $buildMenuOf */
		$buildMenuOf = $this->arguments['of'];

		/** @var string $childrenVariableName */
		$childrenVariableName = $this->arguments['as'];

		$children = $buildMenuOf->getChildren();

		$this->templateVariableContainer->add($childrenVariableName, $children);
		$content = $this->renderChildren();
		$this->templateVariableContainer->remove($childrenVariableName);

		return $content;
	}
}
