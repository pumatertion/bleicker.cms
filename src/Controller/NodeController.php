<?php

namespace Bleicker\Cms\Controller;

use Bleicker\Framework\Controller\AbstractController;

/**
 * Class NodeController
 *
 * @package Bleicker\Cms\Controller
 */
class NodeController extends AbstractController {

	/**
	 * @return string
	 */
	public function indexAction() {
		return $this->view->render();
	}

	/**
	 * @param integer $node
	 * @return string
	 */
	public function showAction($node) {
	}
}
