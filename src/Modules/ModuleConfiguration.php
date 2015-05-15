<?php

namespace Bleicker\Cms\Modules;

use Bleicker\ObjectManager\ObjectManager;

/**
 * Class ModuleConfiguration
 *
 * @package Bleicker\Cms\Modules
 */
class ModuleConfiguration implements ModuleConfigurationInterface {

	/**
	 * @var string
	 */
	protected $label;

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * @var string
	 */
	protected $group = ModuleInterface::UNDEFINED_GROUP;

	/**
	 * @var string
	 */
	protected $className;

	/**
	 * @var array
	 */
	protected $allowedRoles = [];

	/**
	 * @param string $className
	 * @param string $label
	 * @param string $description
	 * @param string $group
	 */
	public function __construct($className, $label, $description, $group) {
		$this->className = $className;
		$this->label = $label;
		$this->description = $description;
		$this->group = $group;
	}

	/**
	 * @param string $className
	 * @param string $label
	 * @param string $description
	 * @param string $group
	 * @return ModuleConfigurationInterface
	 */
	public static function register($className, $label, $description, $group) {
		/** @var ModuleConfigurationsInterface $configurations */
		$configurations = ObjectManager::get(ModuleConfigurationsInterface::class, function () {
			$configurations = new ModuleConfigurations();
			ObjectManager::add(ModuleConfigurationsInterface::class, $configurations, TRUE);
			return $configurations;
		});

		/** @var ModuleConfigurationInterface $configuration */
		$reflection = new \ReflectionClass(static::class);
		$configuration = $reflection->newInstanceArgs(func_get_args());
		$configurations->add($configuration->getClassName(), $configuration);
		return $configuration;
	}

	/**
	 * @return string
	 */
	public function getClassName() {
		return $this->className;
	}

	/**
	 * @return string
	 */
	public function getGroup() {
		return $this->group;
	}

	/**
	 * @return string
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param string $roleName
	 * @return boolean
	 */
	public function allowsRoleName($roleName) {
		return array_key_exists($roleName, $this->allowedRoles);
	}

	/**
	 * @param string $roleName
	 * @return $this
	 */
	public function allowRoleName($roleName) {
		$this->allowedRoles[$roleName] = TRUE;
		return $this;
	}

	/**
	 * @param string $roleName
	 * @return $this
	 */
	public function forbidRoleName($roleName) {
		if ($this->allowsRoleName($roleName)) {
			unset($this->allowedRoles[$roleName]);
		}
		return $this;
	}
}
