<?php

namespace Bleicker\Cms\ViewHelpers\Menus;

use Bleicker\Nodes\NodeInterface;
use Bleicker\Nodes\NodeService;
use Bleicker\Nodes\NodeServiceInterface;
use Bleicker\ObjectManager\ObjectManager;
use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class PageTreeViewHelper
 *
 * @package Bleicker\Cms\ViewHelpers\Menus
 */
class PageTreeViewHelper extends AbstractViewHelper {

	/**
	 * @var boolean
	 */
	protected $escapeOutput = FALSE;

	/**
	 * @var NodeServiceInterface
	 */
	protected $nodeService;

	public function __construct() {
		$this->nodeService = ObjectManager::get(NodeServiceInterface::class, NodeService::class);
	}

	/**
	 * Initialize the arguments.
	 *
	 * @return void
	 * @api
	 */
	public function initializeArguments() {
		$this->registerArgument('of', 'mixed', 'The start page', FALSE);
		$this->registerArgument('as', 'string', 'The name of children variable', FALSE, 'children');
	}

	/**
	 * @return string|NULL
	 */
	public function render() {
		/** @var NodeInterface $of */
		$of = $this->arguments['of'];
		/** @var string $childrenVariableName */
		$childrenVariableName = $this->arguments['as'];
		if ($of instanceof NodeInterface) {
			$children = $this->nodeService->getPages($of);
			$this->templateVariableContainer->add($childrenVariableName, $children);
			$content = $this->renderChildren();
			$this->templateVariableContainer->remove($childrenVariableName);
			return $content;
		}
		return NULL;
	}
}
