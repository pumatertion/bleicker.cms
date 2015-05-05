<?php

namespace Bleicker\Cms\ViewHelpers;

use Bleicker\Nodes\Configuration\NodeConfigurationInterface;
use Bleicker\Nodes\Configuration\NodeTypeConfigurationsInterface;
use Bleicker\Nodes\NodeInterface;
use Bleicker\ObjectManager\ObjectManager;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class PossibleChildrenConfigurationsViewHelper
 *
 * @package Bleicker\Cms\ViewHelpers
 */
class PossibleChildrenConfigurationsViewHelper extends AbstractViewHelper {

	/**
	 * @var NodeTypeConfigurationsInterface
	 */
	protected $nodeTypeConfigurations;

	public function __construct() {
		$this->nodeTypeConfigurations = ObjectManager::get(NodeTypeConfigurationsInterface::class);
	}

	/**
	 * Initialize the arguments.
	 *
	 * @return void
	 * @api
	 */
	public function initializeArguments() {
		$this->registerArgument('node', 'mixed', 'NodeInterface for wich the possible Elements should be returned', TRUE);
	}

	/**
	 * @return Collection
	 */
	public function render() {
		/** @var NodeInterface $node */
		$node = $this->arguments['node'];
		if ($node === NULL) {
			$node = $this->renderChildren();
		}

		$configurations = new ArrayCollection($this->nodeTypeConfigurations->storage());
		$nodeConfigurations = $configurations->filter(function (NodeConfigurationInterface $configuration) use ($node) {
			return $node !== NULL && $node->getNodeType() === $configuration->getClassName();
		});

		$possibleConfigurations = $configurations->filter(function (NodeConfigurationInterface $configuration) use ($node, $nodeConfigurations) {
			/** @var NodeConfigurationInterface $nodeConfiguration */
			foreach ($nodeConfigurations as $nodeConfiguration) {
				if ($nodeConfiguration->allowsChild($configuration->getClassName())) {
					return TRUE;
				}
			}
		});

		return $possibleConfigurations;
	}
}
