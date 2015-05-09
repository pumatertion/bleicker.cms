<?php

namespace Bleicker\Cms\Controller;

use Bleicker\Converter\Converter;
use Bleicker\Framework\Controller\AbstractController;
use Bleicker\Framework\Utility\Arrays;
use Bleicker\Nodes\Configuration\NodeTypeConfigurations;
use Bleicker\Nodes\Configuration\NodeTypeConfigurationsInterface;
use Bleicker\Nodes\NodeInterface;
use Bleicker\Nodes\NodeService;
use Bleicker\Nodes\NodeServiceInterface;
use Bleicker\ObjectManager\ObjectManager;

/**
 * Class NodeController
 *
 * @package Bleicker\Cms\Controller
 */
class NodeController extends AbstractController {

	/**
	 * @var NodeServiceInterface
	 */
	protected $nodeService;

	/**
	 * @var NodeTypeConfigurationsInterface
	 */
	protected $nodeTypeConfigurations;

	public function __construct() {
		parent::__construct();
		$this->nodeService = ObjectManager::get(NodeServiceInterface::class, NodeService::class);
		$this->nodeTypeConfigurations = ObjectManager::get(NodeTypeConfigurationsInterface::class, NodeTypeConfigurations::class);
	}

	/**
	 * @return string
	 */
	public function indexAction() {
		/** @var NodeInterface $node */
		$sites = $this->nodeService->findSites();
		$node = $sites->first();
		$page = $node;
		return $this->view->assign('node', $node)->assign('page', $page)->assign('sites', $sites)->render();
	}

	/**
	 * @param string $node
	 * @return string
	 */
	public function showAction($node) {
		/** @var NodeInterface $node */
		$node = $this->nodeService->get($node);
		$page = $this->nodeService->locatePage($node);
		if ($page === NULL) {
			$page = $this->nodeService->locateSite($node);
		}
		$sites = $this->nodeService->findSites();
		return $this->view->assign('node', $node)->assign('page', $page)->assign('sites', $sites)->render();
	}

	/**
	 * @param string $nodeType
	 * @return string
	 */
	public function addAction($nodeType) {
		/** @var NodeInterface $node */
		$node = Converter::convert([], $this->nodeTypeConfigurations->get($nodeType)->getClassName());
		return $this->view->assign('node', $node)->render();
	}

	/**
	 * @param $reference
	 * @return void
	 */
	public function addWithReferenceAction($reference) {
		$reference = $this->nodeService->get($reference);
		$mode = $this->request->getContent('mode');
		$nodeType = $this->request->getContent('nodeType');

		/** @var NodeInterface $node */
		$node = Converter::convert([], $this->nodeTypeConfigurations->get($nodeType)->getClassName());

		switch ($mode) {
			case 'into':
				$this->nodeService->addChild($node, $reference);
				break;
			case 'after':
				$this->nodeService->addAfter($node, $reference);
				break;
			case 'before':
				$this->nodeService->addBefore($node, $reference);
				break;
		}
		$this->redirect('/nodemanager/' . $node->getId());
	}

	/**
	 * @param string $node
	 * @return void
	 */
	public function updateAction($node) {
		$node = $this->nodeService->get($node);
		$source = $this->request->getContents();
		Arrays::setValueByPath($source, 'id', $node->getId());
		/** @var NodeInterface $converted */
		$converted = Converter::convert($source, $node->getNodeType());
		$this->nodeService->update($converted);
		$this->redirect('/nodemanager/' . $converted->getId());
	}

	/**
	 * @param string $node
	 * @return void
	 */
	public function removeAction($node) {
		$node = $this->nodeService->get($node);
		if ($this->locales->isLocalizationMode()) {
			$this->nodeService->removeTranslations($node, $this->locales->getSystemLocale());
			$this->redirect('/nodemanager/' . $node->getId());
		}
		$parentNode = $node->getParent();
		$this->nodeService->remove($node);
		if ($parentNode !== NULL) {
			$this->redirect('/nodemanager/' . $parentNode->getId());
		}
		$this->redirect('/nodemanager');
	}

	/**
	 * @param string $nodeType
	 * @return void
	 */
	public function createAction($nodeType) {
		/** @var NodeInterface $node */
		$node = Converter::convert($this->request->getContents(), $this->nodeTypeConfigurations->get($nodeType)->getClassName());
		$this->nodeService->add($node);
		$this->redirect('/nodemanager/' . $node->getId(), 303);
	}
}
