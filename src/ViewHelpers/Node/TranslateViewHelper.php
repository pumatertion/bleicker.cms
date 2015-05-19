<?php

namespace Bleicker\Cms\ViewHelpers\Node;

use Bleicker\Framework\Validation\ResultsInterface;
use Bleicker\Nodes\NodeInterface;
use Bleicker\Nodes\NodeService;
use Bleicker\Nodes\NodeServiceInterface;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Translation\LocalesInterface;
use Bleicker\Translation\Translation;
use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class TranslateViewHelper
 *
 * @package Bleicker\Cms\ViewHelpers\Node
 */
class TranslateViewHelper extends AbstractViewHelper {

	/**
	 * @var boolean
	 */
	protected $escapeChildren = FALSE;

	/**
	 * @var LocalesInterface
	 */
	protected $locales;

	/**
	 * @var NodeServiceInterface
	 */
	protected $nodeService;

	public function __construct() {
		$this->locales = ObjectManager::get(LocalesInterface::class);
		$this->nodeService = ObjectManager::get(NodeServiceInterface::class, NodeService::class);
	}

	/**
	 * Initialize the arguments.
	 *
	 * @return void
	 * @api
	 */
	public function initializeArguments() {
		$this->registerArgument('node', 'mixed', 'NodeInterface for wich the possible Elements should be returned', TRUE);
		$this->registerArgument('propertyName', 'string', 'Propertyname the translation is for', TRUE);
	}

	/**
	 * @return array
	 */
	public function render() {
		/** @var string $propertyName */
		$propertyName = $this->arguments['propertyName'];

		/** @var NodeInterface $node */
		$node = $this->arguments['node'];
		if ($node === NULL) {
			$node = $this->renderChildren();
		}

		$translation = new Translation($propertyName, $this->locales->getSystemLocale());

		$valueFromValidation = $this->getValidationResultPropertyValue($node, $translation);
		if($valueFromValidation !== NULL){
			return $valueFromValidation;
		}

		if ($node->hasTranslation($translation)) {
			return $valueFromValidation === NULL ? $node->getTranslation($translation)->getValue() : $valueFromValidation;
		}

		return call_user_func(array($node, 'get' . ucfirst($propertyName)));
	}

	/**
	 * @param NodeInterface $node
	 * @param Translation $translation
	 * @return mixed|NULL
	 */
	protected function getValidationResultPropertyValue(NodeInterface $node, Translation $translation) {
		/** @var ResultsInterface $validationResults */
		$validationResults = ObjectManager::get(ResultsInterface::class);
		$allValidationResults = $validationResults->storage();
		foreach ($allValidationResults as $result) {
			if ($result->getPropertyPath() === $translation->getPropertyName()) {
				return $result->getPropertyValue();
			}
		}
	}
}
