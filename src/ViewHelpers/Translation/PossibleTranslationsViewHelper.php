<?php

namespace Bleicker\Cms\ViewHelpers\Translation;

use Bleicker\Nodes\NodeInterface;
use Bleicker\Nodes\NodeService;
use Bleicker\Nodes\NodeServiceInterface;
use Bleicker\Nodes\NodeTranslation;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Translation\LocalesInterface;
use Bleicker\Translation\Translation;
use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class PossibleTranslationsViewHelper
 *
 * @package Bleicker\Cms\ViewHelpers\Translation
 */
class PossibleTranslationsViewHelper extends AbstractViewHelper {

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
		if($node === NULL){
			$node = $this->renderChildren();
		}

		$result = $this->locales->storage();
		return $result;
	}
}
