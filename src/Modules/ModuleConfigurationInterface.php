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
	 * @param string $uri
	 * @return ModuleConfigurationInterface
	 */
	public static function register($className, $label, $description, $group, $uri);

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
	 * @return string
	 */
	public function getUri();

	/**
	 * @param string $roleName
	 * @return boolean
	 */
	public function allowsRoleName($roleName);

	/**
	 * @param string $roleName
	 * @return $this
	 */
	public function addAllowedRoleName($roleName);

	/**
	 * @param string $roleName
	 * @return $this
	 */
	public function removeAllowedRoleName($roleName);
}