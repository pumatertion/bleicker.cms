<?php

namespace Bleicker\Cms\Modules;

use Bleicker\Container\AbstractContainer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Bleicker\Account\RoleInterface;

/**
 * Class ModuleConfigurations
 *
 * @package Bleicker\Cms\Modules
 */
class ModuleConfigurations extends AbstractContainer implements ModuleConfigurationsInterface {

	/**
	 * @var array
	 */
	public static $storage = [];

	/**
	 * @param string $alias
	 * @return ModuleConfigurationInterface
	 */
	public static function get($alias) {
		return parent::get($alias);
	}

	/**
	 * @param string $alias
	 * @param ModuleConfigurationInterface $data
	 * @return static
	 */
	public static function add($alias, $data) {
		return parent::add($alias, $data);
	}

	/**
	 * @param Collection $roles
	 * @return Collection
	 */
	public static function getAllowedByRoles(Collection $roles) {
		$configurations = new ArrayCollection(static::storage());
		$allowed = $configurations->filter(function(ModuleConfigurationInterface $moduleConfiguration) use ($roles){
			/** @var RoleInterface $role */
			foreach($roles as $role){
				if(!$moduleConfiguration->allowsRoleName($role->getName())){
					return FALSE;
				}
			}
			return TRUE;
		});
		return $allowed;
	}
}
