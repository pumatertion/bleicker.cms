<?php

namespace Bleicker\Cms\Controller;

use Bleicker\Converter\Converter;
use Bleicker\Framework\Controller\AbstractController;
use Bleicker\Framework\Utility\Arrays;
use Bleicker\Nodes\NodeInterface;
use Bleicker\Nodes\NodeServiceInterface;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Registry\Registry;

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

	public function __construct() {
		parent::__construct();
		$this->nodeService = ObjectManager::get(NodeServiceInterface::class);
	}

	/**
	 * @return string
	 */
	public function indexAction() {
		$sites = $this->nodeService->findSites();
		return $this->view->assign('sites', $sites)->assign('page', $sites->first())->render();
	}

	/**
	 * @param string $node
	 * @return string
	 */
	public function showAction($node) {
		/** @var NodeInterface $node */
		$node = $this->nodeService->get($node);
		$page = $this->nodeService->locatePage($node);
		$root = $this->nodeService->locateRoot($node);
		$sites = $this->nodeService->findSites();
		return $this->view->assign('node', $node)->assign('page', $page)->assign('root', $root)->assign('sites', $sites)->render();
	}

	/**
	 * @param string $nodeType
	 * @return string
	 */
	public function addAction($nodeType) {
		$node = ObjectManager::get(Registry::get('nodetypes.' . $nodeType));
		return $this->view->assign('node', $node)->render();
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
		$parentNode = $node->getParent();
		$this->nodeService->remove($node);

		if ($parentNode !== NULL) {
			$this->redirect('/nodemanager/' . $parentNode);
		}
		$this->redirect('/nodemanager');
	}

	/**
	 * @param string $nodeType
	 * @return void
	 */
	public function createAction($nodeType) {
		/** @var NodeInterface $node */
		$node = Converter::convert($this->request->getContents(), Registry::get('nodetypes.' . $nodeType));
		$this->nodeService->add($node);
		$this->redirect('/nodemanager/' . $node->getId(), 303);
	}
}
