<?php

namespace Bleicker\Cms\Modules;

/**
 * Class ModuleTrait
 *
 * @package Bleicker\Cms\Modules
 */
trait ModuleTrait {

	/**
	 * @param string $label
	 * @param string $description
	 * @param string $group
	 * @param string $uri
	 * @return ModuleConfigurationInterface
	 */
	public static function register($label, $description, $group, $uri) {
		$arguments = ['className' => static::class];
		$arguments = array_merge($arguments, func_get_args());
		return call_user_func_array(ModuleConfiguration::class . '::register', $arguments);
	}
}
