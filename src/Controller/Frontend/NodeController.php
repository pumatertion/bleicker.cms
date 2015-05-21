<?php

namespace Bleicker\Cms\Controller\Frontend;

use Bleicker\Cms\Modules\ModuleTrait;
use Bleicker\Framework\Controller\AbstractController;
use Bleicker\Nodes\NodeInterface;
use Bleicker\Nodes\NodeService;
use Bleicker\Nodes\NodeServiceInterface;
use Bleicker\ObjectManager\ObjectManager;

/**
 * Class NodeController
 *
 * @package Bleicker\Cms\Controller\Frontend
 */
class NodeController extends AbstractController {

	/**
	 * @var NodeServiceInterface
	 */
	protected $nodeService;

	public function __construct() {
		parent::__construct();
		$this->nodeService = ObjectManager::get(NodeServiceInterface::class, NodeService::class);
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
		return $this->view->assign('node', $node)->assign('page', $page)->assign('sites', $sites)->assign('validationException', $this->getValidationException())->render();
	}
}
