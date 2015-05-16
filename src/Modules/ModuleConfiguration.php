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
	protected $uri;

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
	 * @param string $uri
	 */
	public function __construct($className, $label, $description, $group, $uri) {
		$this->className = $className;
		$this->label = $label;
		$this->description = $description;
		$this->group = $group;
		$this->uri = $uri;
	}

	/**
	 * @param string $className
	 * @param string $label
	 * @param string $description
	 * @param string $group
	 * @param string $uri
	 * @return ModuleConfigurationInterface
	 */
	public static function register($className, $label, $description, $group, $uri) {
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
	 * @return string
	 */
	public function getUri() {
		return $this->uri;
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
