<?php
namespace Bleicker\Cms\Modules;

/**
 * Class ModuleConfiguration
 *
 * @package Bleicker\Cms\Modules
 */
interface ModuleConfigurationInterface {

	/**
	 * @param string $className
	 * @param string $label
	 * @param string $description
	 * @param string $group
	 * @return ModuleConfigurationInterface
	 */
	public static function register($className, $label, $description, $group);

	/**
	 * @return string
	 */
	public function getClassName();

	/**
	 * @return string
	 */
	public function getGroup();

	/**
	 * @return string
	 */
	public function getLabel();

	/**
	 * @return string
	 */
	public function getDescription();

	/**
	 * @param string $roleName
	 * @return boolean
	 */
	public function allowsRoleName($roleName);

	/**
	 * @param $roleName
	 * @return $this
	 */
	public function allowRoleName($roleName);

	/**
	 * @param $roleName
	 * @return $this
	 */
	public function forbidRoleName($roleName);
}