<?php

namespace Bleicker\Cms\Controller;

use Bleicker\Cms\Modules\ModuleInterface;
use Bleicker\Cms\Modules\ModuleTrait;
use Bleicker\Context\ContextInterface;
use Bleicker\Converter\Converter;
use Bleicker\Framework\Controller\AbstractController;
use Bleicker\Framework\Utility\Arrays;
use Bleicker\Framework\Validation\Exception\ValidationException;
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
class NodeController extends AbstractController implements ModuleInterface {

	use ModuleTrait;

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
		ObjectManager::get(ContextInterface::class)->remove(NodeService::SHOW_HIDDEN_CONTEXT_KEY)->add(NodeService::SHOW_HIDDEN_CONTEXT_KEY, TRUE);
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

	/**
	 * @param $reference
	 * @throws ValidationException
	 * @return void
	 */
	public function addWithReferenceAction($reference) {
		try {
			$reference = $this->nodeService->get($reference);
			$mode = $this->request->getContent('mode');
			$nodeType = $this->request->getContent('nodeType');
			$source = $this->request->getContents();

			/** @var NodeInterface $node */
			$node = Converter::convert($source, $this->nodeTypeConfigurations->get($nodeType)->getClassName());

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
		} catch (ValidationException $exception) {
			$exception->setControllerName(static::class)->setMethodName('showAction')->setMethodArguments(func_get_args());
			throw $exception;
		}
	}

	/**
	 * @param string $node
	 * @throws ValidationException
	 * @return void
	 */
	public function updateAction($node) {
		try {
			$node = $this->nodeService->get($node);
			$source = $this->request->getContents();
			$source = array_merge($source, $this->request->getParentRequest()->files->all());
			Arrays::setValueByPath($source, 'id', $node->getId());
			/** @var NodeInterface $converted */
			$converted = Converter::convert($source, $node->getNodeType());
			$this->nodeService->update($converted);
			$this->redirect('/nodemanager/' . $converted->getId());
		} catch (ValidationException $exception) {
			$exception->setControllerName(static::class)->setMethodName('showAction')->setMethodArguments(func_get_args());
			throw $exception;
		}
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
	 * @return void
	 * @throws ValidationException
	 */
	public function addAction() {
		try {
			$nodeType = $this->request->getContent('nodeType');
			$source = $this->request->getContents();
			/** @var NodeInterface $node */
			$node = Converter::convert($source, $this->nodeTypeConfigurations->get($nodeType)->getClassName());
			$this->nodeService->add($node);
			$this->redirect('/nodemanager/' . $node->getId(), 303);
		} catch (ValidationException $exception) {
			$exception->setControllerName(static::class)->setMethodName('indexAction')->setMethodArguments(func_get_args());
			throw $exception;
		}
	}
}
