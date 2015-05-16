<?php

namespace Bleicker\Cms\ViewHelpers\Modules;

use Bleicker\Authentication\AuthenticationManager;
use Bleicker\Authentication\AuthenticationManagerInterface;
use Bleicker\Cms\Modules\ModuleConfigurations;
use Bleicker\Cms\Modules\ModuleConfigurationsInterface;
use Bleicker\ObjectManager\ObjectManager;
use Doctrine\Common\Collections\Collection;
use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class ModuleConfigurationsViewHelper
 *
 * @package Bleicker\Cms\ViewHelpers\Modules
 */
class ModuleConfigurationsViewHelper extends AbstractViewHelper {

	/**
	 * @var ModuleConfigurationsInterface
	 */
	protected $moduleConfigurations;

	/**
	 * @var AuthenticationManagerInterface
	 */
	protected $authenticationManager;

	public function __construct() {
		$this->moduleConfigurations = ObjectManager::get(ModuleConfigurationsInterface::class, function () {
			$moduleConfigurations = new ModuleConfigurations();
			ObjectManager::add(ModuleConfigurationsInterface::class, $moduleConfigurations, TRUE);
			return $moduleConfigurations;
		});
		$this->authenticationManager = ObjectManager::get(AuthenticationManagerInterface::class, function () {
			$authenticationManager = new AuthenticationManager();
			ObjectManager::add(AuthenticationManagerInterface::class, $authenticationManager, TRUE);
			return $authenticationManager;
		});
	}

	/**
	 * @return Collection
	 */
	public function render() {
		$moduleConfigurations = $this->moduleConfigurations->getAllowedByRoles($this->authenticationManager->getRoles());
		return $moduleConfigurations;
	}
}
