<?php

namespace Bleicker\Cms\TypeConverter\Node;

use Bleicker\Converter\TypeConverter\TypeConverterInterface;
use Bleicker\Framework\Utility\Arrays;
use Bleicker\Nodes\NodeServiceInterface;
use Bleicker\NodeTypes\Site;
use Bleicker\ObjectManager\ObjectManager;

/**
 * Class SiteTypeConverter
 *
 * @package Bleicker\Cms\TypeConverter\Node
 */
class SiteTypeConverter implements TypeConverterInterface {

	/**
	 * @var NodeServiceInterface
	 */
	protected $nodeService;

	public function __construct() {
		$this->nodeService = ObjectManager::get(NodeServiceInterface::class);
	}

	/**
	 * @param array $source
	 * @param string $targetType
	 * @return boolean
	 */
	public static function canConvert($source = NULL, $targetType) {
		if (is_array($source) && $targetType === Site::class) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * @param array $source
	 * @return Site
	 */
	public function convert($source) {
		if ($this->isUpdate($source)) {
			return $this->getUpdated($source);
		}
		return $this->getNew($source);
	}

	/**
	 * Returns true if value of path "className.id" not null
	 *
	 * @param array $source
	 * @return boolean
	 */
	protected function isUpdate(array $source) {
		return $this->getIdFromSource($source) !== NULL;
	}

	/**
	 * @param array $source
	 * @return mixed
	 */
	protected function getIdFromSource(array $source) {
		return Arrays::getValueByPath($source, $this->getIdPath());
	}

	/**
	 * @return string
	 */
	protected function getIdPath() {
		return 'id';
	}

	/**
	 * Returns a new site mapped with source arguments
	 *
	 * @param array $source
	 * @return Site
	 */
	protected function getNew(array $source) {
		$node = new Site();
		$node->setTitle(Arrays::getValueByPath($source, 'title') === NULL ? '' : Arrays::getValueByPath($source, 'title'));
		return $node;
	}

	/**
	 * Returns an updated site mapped with source arguments
	 *
	 * @param array $source
	 * @return Site
	 */
	protected function getUpdated(array $source) {
		$nodeId = Arrays::getValueByPath($source, $this->getIdPath());
		Arrays::unsetValueByPath($source, $this->getIdPath());
		/** @var Site $node */
		$node = $this->nodeService->get($nodeId);
		$node->setTitle(Arrays::getValueByPath($source, 'title') === NULL ? '' : Arrays::getValueByPath($source, 'title'));
		return $node;
	}
}
