<?php

namespace Bleicker\Cms\ViewHelpers\Authentication;

use Bleicker\Authentication\AuthenticationManagerInterface;
use Bleicker\ObjectManager\ObjectManager;
use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class AccountsViewHelper
 *
 * @package Bleicker\Cms\ViewHelpers\Authentication
 */
class AccountsViewHelper extends AbstractViewHelper {

	/**
	 * @var boolean
	 */
	protected $escapeOutput = FALSE;

	/**
	 * @var AuthenticationManagerInterface
	 */
	protected $authenticationManager;

	public function __construct() {
		$this->authenticationManager = ObjectManager::get(AuthenticationManagerInterface::class);
	}

	/**
	 * Initialize the arguments.
	 *
	 * @return void
	 * @api
	 */
	public function initializeArguments() {
		$this->registerArgument('as', 'string', 'The name of accounts variable', FALSE, 'accounts');
	}

	/**
	 * @return string
	 */
	public function render() {
		$variableName = $this->arguments['as'];
		$accounts = $this->authenticationManager->getAccounts();

		$result = NULL;

		$this->templateVariableContainer->add($variableName, $accounts);
		$result .= $this->renderChildren();
		$this->templateVariableContainer->remove($variableName);

		return $result;
	}
}
