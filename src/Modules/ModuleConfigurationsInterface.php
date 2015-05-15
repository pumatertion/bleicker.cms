<?php
namespace Bleicker\Cms\Modules;

use Bleicker\Container\Exception\AliasAlreadyExistsException;
use Doctrine\Common\Collections\Collection;

/**
 * Class ModuleConfigurations
 *
 * @package Bleicker\Cms\Modules
 */
interface ModuleConfigurationsInterface {

	/**
	 * @return array
	 */
	public static function storage();

	/**
	 * @param string $alias
	 * @return boolean
	 */
	public static function has($alias);

	/**
	 * @param string $alias
	 * @param ModuleConfigurationInterface $data
	 * @return static
	 * @throws AliasAlreadyExistsException
	 */
	public static function add($alias, $data);

	/**
	 * @return static
	 */
	public static function prune();

	/**
	 * @param string $alias
	 * @return ModuleConfigurationInterface
	 */
	public static function get($alias);

	/**
	 * @param string $alias
	 * @return static
	 */
	public static function remove($alias);

	/**
	 * @param Collection $roles
	 * @return Collection
	 */
	public static function getAllowedByRoles(Collection $roles);
}