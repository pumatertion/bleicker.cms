<?php

namespace Bleicker\Cms\TypeConverter\Node;

use Bleicker\Converter\TypeConverter\TypeConverterInterface;
use Bleicker\Framework\Utility\Arrays;
use Bleicker\Nodes\NodeServiceInterface;
use Bleicker\NodeTypes\MultiColumn;
use Bleicker\ObjectManager\ObjectManager;

/**
 * Class MultiColumnTypeConverter
 *
 * @package Bleicker\Cms\TypeConverter\Node
 */
class MultiColumnTypeConverter implements TypeConverterInterface {

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
		if (is_array($source) && $targetType === MultiColumn::class) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * @param array $source
	 * @return MultiColumn
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
	 * @return MultiColumn
	 */
	protected function getNew(array $source) {
		$node = new MultiColumn();
		return $node;
	}

	/**
	 * Returns an updated site mapped with source arguments
	 *
	 * @param array $source
	 * @return MultiColumn
	 */
	protected function getUpdated(array $source) {
		$nodeId = Arrays::getValueByPath($source, $this->getIdPath());
		Arrays::unsetValueByPath($source, $this->getIdPath());
		/** @var MultiColumn $node */
		$node = $this->nodeService->get($nodeId);
		return $node;
	}
}
