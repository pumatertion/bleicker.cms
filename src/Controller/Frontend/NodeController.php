<?php

namespace Bleicker\Cms\Controller\Frontend;

use Bleicker\Cms\Modules\ModuleTrait;
use Bleicker\Context\ContextInterface;
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
		$site = $this->nodeService->findDomainSites($this->context->get(ContextInterface::DOMAIN_CONTEXT))->first();
		return $this->view->assign('node', $site)->assign('page', $site)->assign('site', $site)->assign('validationException', $this->getValidationException())->render();
	}

	/**
	 * @param string $node
	 * @return string
	 * @todo make sure only nodes from current domain
	 */
	public function showAction($node) {
		/** @var NodeInterface $node */
		$node = $this->nodeService->get($node);
		$page = $this->nodeService->locatePage($node);
		$site = $this->nodeService->findDomainSites($this->context->get(ContextInterface::DOMAIN_CONTEXT))->first();
		if ($page === NULL) {
			$page = $site;
		}
		return $this->view->assign('node', $node)->assign('page', $page)->assign('site', $site)->assign('validationException', $this->getValidationException())->render();
	}
}
