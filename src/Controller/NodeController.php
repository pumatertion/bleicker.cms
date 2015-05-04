<?php

namespace Bleicker\Cms\Controller;

use Bleicker\Converter\Converter;
use Bleicker\Framework\Controller\AbstractController;
use Bleicker\Nodes\NodeInterface;
use Bleicker\Nodes\NodeServiceInterface;
use Bleicker\NodeTypes\Headline;
use Bleicker\NodeTypes\Page;
use Bleicker\NodeTypes\Text;
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
		$node = $this->nodeService->getNode($node);
		$page = $this->nodeService->locatePage($node);
		$root = $this->nodeService->locateRoot($node);
		$sites = $this->nodeService->findSites();
		return $this->view->assign('node', $node)->assign('page', $page)->assign('root', $root)->assign('sites', $sites)->render();
	}

	/**
	 * @param string $nodeType
	 * @return string
	 */
	public function formAction($nodeType) {
		$node = ObjectManager::get(Registry::get('nodetypes.' . $nodeType));
		return $this->view->assign('node', $node)->render();
	}

	/**
	 * @param string $nodeType
	 * @return string
	 */
	public function createAction($nodeType) {
		/** @var NodeInterface $node */
		$node = Converter::convert($this->request->getContents(), Registry::get('nodetypes.' . $nodeType));
		$this->nodeService->add($node);
		$this->redirect('/nodemanager/' . $node->getId());
	}

	/**
	 * @todo remove this
	 */
	protected function buildPagesAndContent() {

		$_1 = new Page();
		$_1->setTitle(uniqid('Page '));

		$_2 = new Page();
		$_2->setTitle(uniqid('Page '));

		$_3 = new Page();
		$_3->setTitle(uniqid('Page '));

		$_4 = new Page();
		$_4->setTitle(uniqid('Page '));

		$_5 = new Page();
		$_5->setTitle(uniqid('Page '));

		$_6 = new Page();
		$_6->setTitle(uniqid('Page '));

		$_1->addChild($_2->addChild($_3->addChild($_4->addChild($_5->addChild($_6)))));

		$header = new Headline();
		$header->setTitle('Lorem Ipsum');
		$header->setSubtitle('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.');

		$text = new Text();
		$text->setBody('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.

Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.

Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.');

		$_1->addChild($header)->addChildAfter($text, $header);
		$this->entityManager->persist($_1);
		$this->entityManager->flush();
	}
}
