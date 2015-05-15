<?php

namespace Bleicker\Cms\Modules;

/**
 * Interface ModuleInterface
 *
 * @package Bleicker\Cms\Modules
 */
interface ModuleInterface {

	const MANAGEMENT_GROUP = 'Management', SELF_GROUP = 'Me', UNDEFINED_GROUP = 'Others';

	/**
	 * @param string $label
	 * @param string $description
	 * @param string $group
	 * @param array $allowedChildren
	 * @param array $forbiddenChildren
	 * @return void
	 */
	public static function register($label, $description, $group, array $allowedChildren = [], array $forbiddenChildren = []);
}
