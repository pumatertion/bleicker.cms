<?php

namespace Bleicker\Cms\ViewHelpers\Node;

use Bleicker\Nodes\NodeInterface;
use Bleicker\Nodes\NodeServiceInterface;
use Bleicker\Nodes\NodeTranslation;
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
		$this->nodeService = ObjectManager::get(NodeServiceInterface::class);
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
		if ($node->hasTranslation($translation)) {
			return $node->getTranslation($translation)->getValue();
		}

		return call_user_func(array($node, 'get' . ucfirst($propertyName)));
	}
}
