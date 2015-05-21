<?php

namespace Bleicker\Cms\ViewHelpers\Validation;

use Bleicker\Framework\Validation\MessageInterface;
use Bleicker\Framework\Validation\ResultCollectionInterface;
use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class ValidationResultsViewHelper
 *
 * @package Bleicker\Cms\ViewHelpers\Validation
 */
class ValidationResultsViewHelper extends AbstractViewHelper {

	/**
	 * @var boolean
	 */
	protected $escapeChildren = FALSE;

	/**
	 * @var boolean
	 */
	protected $escapeOutput = FALSE;

	/**
	 * Initialize the arguments.
	 *
	 * @return void
	 */
	public function initializeArguments() {
		$this->registerArgument('as', 'string', 'Variablename of assigned results', TRUE, 'validationResults');
		$this->registerArgument('propertyPath', 'string', 'Show only validation results matching this property path', TRUE, NULL);
		$this->registerArgument('results', 'mixed', 'Validation Results', TRUE);
	}

	/**
	 * @return ResultCollectionInterface
	 */
	public function render() {
		/** @var string $as */
		$as = $this->arguments['as'];
		$this->templateVariableContainer->add($as, $this->getValidationResults());
		$result = $this->renderChildren();
		$this->templateVariableContainer->remove($as);
		return $result;
	}

	/**
	 * @return ResultCollectionInterface
	 */
	protected function getValidationResults() {
		$propertyPath = $this->arguments['propertyPath'];
		/** @var ResultCollectionInterface $validationResults */
		$validationResults = $this->arguments['results'];
		if (!($validationResults instanceof ResultCollectionInterface)) {
			return NULL;
		}
		if ($propertyPath !== NULL) {
			$validationResults = $validationResults->filter(function (MessageInterface $message) use ($propertyPath) {
				return $propertyPath === $message->getPropertyPath();
			});
		}
		return $validationResults;
	}
}
