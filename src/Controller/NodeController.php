<?php

namespace Bleicker\Cms\Controller;

use Bleicker\Framework\Controller\AbstractController;
use Bleicker\NodeTypes\Page;

/**
 * Class NodeController
 *
 * @package Bleicker\Cms\Controller
 */
class NodeController extends AbstractController {

	/**
	 * @param string $node
	 * @return string
	 */
	public function indexAction($node = NULL) {

		if($node !== NULL){
			$node = $this->entityManager->find(Page::class, $node);
		}

		if (!$node) {
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

			$this->entityManager->persist($_1);
			$this->entityManager->flush();
		}

		return $this->view->assign('node', $node)->render();
	}
}
