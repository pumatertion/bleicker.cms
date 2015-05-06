<?php

namespace Bleicker\Cms\ViewHelpers\Uri;

use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Routing\ControllerRouteData;
use Bleicker\Routing\RouterInterface;
use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class ActionViewHelper
 *
 * @package Bleicker\Cms\ViewHelpers\Uri
 */
class ActionViewHelper extends AbstractViewHelper {

	/**
	 * Initialize the arguments.
	 *
	 * @return void
	 * @api
	 */
	public function initializeArguments() {
		$this->registerArgument('controller', 'string', 'The controller fqn classname the uri is for', TRUE);
		$this->registerArgument('action', 'string', 'The controller action the uri is for', TRUE, 'indexAction');
		$this->registerArgument('arguments', 'string', 'Controller arguments', FALSE);
		$this->registerArgument('method', 'string', 'Http Method', FALSE, 'get');
	}

	/**
	 * @return string
	 */
	public function render() {
		/** @var RouterInterface $router */
		$router = ObjectManager::get(RouterInterface::class);
		$pattern = $router->getPattern($this->arguments['method'], new ControllerRouteData($this->arguments['controller'], $this->arguments['action']));
		$argumentNames = array_map(function ($name) {
			return "{{$name}}";
		}, array_keys($this->arguments['arguments']));
		$uri = str_replace($argumentNames, array_values($this->arguments['arguments']), $pattern);
		return $uri;
	}
}
