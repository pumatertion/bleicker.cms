<?php

namespace Bleicker\Cms\ViewHelpers\Menus;

use Bleicker\Nodes\NodeInterface;
use Bleicker\Nodes\NodeService;
use Bleicker\Nodes\NodeServiceInterface;
use Bleicker\ObjectManager\ObjectManager;
use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class ContentTreeViewHelper
 *
 * @package Bleicker\Cms\ViewHelpers\Menus
 */
class ContentTreeViewHelper extends AbstractViewHelper {

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
		$this->registerArgument('of', 'mixed', 'The start content', FALSE);
		$this->registerArgument('as', 'string', 'The name of children variable', FALSE, 'content');
		$this->registerArgument('partial', 'string', 'The name of children variable', FALSE, 'partial');
	}

	/**
	 * @return string|null
	 */
	public function render() {
		/** @var NodeInterface $of */
		$of = $this->arguments['of'];
		/** @var string $childrenVariableName */
		$childrenVariableName = $this->arguments['as'];
		/** @var string $partialVariableName */
		$partialVariableName = $this->arguments['partial'];

		if ($of instanceof NodeInterface) {
			$children = $this->nodeService->getContent($of);
			$result = '';
			foreach ($children as $content) {
				$partialPath = str_replace('\\', DIRECTORY_SEPARATOR, get_class($content));
				$this->templateVariableContainer->add($childrenVariableName, $content);
				$this->templateVariableContainer->add($partialVariableName, $partialPath);
				$result .= $this->renderChildren();
				$this->templateVariableContainer->remove($partialVariableName);
				$this->templateVariableContainer->remove($childrenVariableName);
			}
			return $result;
		}
		return NULL;
	}
}
