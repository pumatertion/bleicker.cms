<?php

namespace Bleicker\Cms\TypeConverter\Node;

use Bleicker\Converter\AbstractTypeConverter;
use Bleicker\Framework\Utility\Arrays;
use Bleicker\Nodes\NodeService;
use Bleicker\Nodes\NodeServiceInterface;
use Bleicker\Nodes\NodeTranslation;
use Bleicker\NodeTypes\GridElement;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Nodes\Locale;

/**
 * Class GridElementTypeConverter
 *
 * @package Bleicker\Cms\TypeConverter\Node
 */
class GridElementTypeConverter extends AbstractTypeConverter {

	/**
	 * @var NodeServiceInterface
	 */
	protected $nodeService;

	public function __construct() {
		parent::__construct();
		$this->nodeService = ObjectManager::get(NodeServiceInterface::class, NodeService::class);
	}

	/**
	 * @param array $source
	 * @param string $targetType
	 * @return boolean
	 */
	public static function canConvert($source = NULL, $targetType) {
		if (is_array($source) && $targetType === GridElement::class) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * @param array $source
	 * @return GridElement
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
	 * @return GridElement
	 */
	protected function getNew(array $source) {
		$node = new GridElement();
		$node->setColspan(Arrays::getValueByPath($source, 'colspan') === NULL ? : Arrays::getValueByPath($source, 'colspan'));
		$node->setOffset(Arrays::getValueByPath($source, 'offset') === NULL ? : Arrays::getValueByPath($source, 'offset'));
		return $node;
	}

	/**
	 * Returns an updated site mapped with source arguments
	 *
	 * @param array $source
	 * @return GridElement
	 */
	protected function getUpdated(array $source) {

		if ($this->isLocalizationMode()) {
			return $this->getLocalized($source);
		}

		$nodeId = Arrays::getValueByPath($source, $this->getIdPath());
		Arrays::unsetValueByPath($source, $this->getIdPath());
		/** @var GridElement $node */
		$node = $this->nodeService->get($nodeId);
		$node->setColspan(Arrays::getValueByPath($source, 'colspan') === NULL ? : Arrays::getValueByPath($source, 'colspan'));
		$node->setOffset(Arrays::getValueByPath($source, 'offset') === NULL ? : Arrays::getValueByPath($source, 'offset'));
		return $node;
	}

	/**
	 * @param array $source
	 * @return GridElement
	 */
	protected function getLocalized(array $source) {
		$nodeId = Arrays::getValueByPath($source, $this->getIdPath());
		Arrays::unsetValueByPath($source, $this->getIdPath());

		/** @var GridElement $node */
		$node = $this->nodeService->get($nodeId);

		$colspanTranslation = new NodeTranslation('colspan', $this->getNodeLocale(), Arrays::getValueByPath($source, 'colspan'));
		$this->nodeService->addTranslation($node, $colspanTranslation->setNode($node));

		$offsetTranslation = new NodeTranslation('offset', $this->getNodeLocale(), Arrays::getValueByPath($source, 'offset'));
		$this->nodeService->addTranslation($node, $offsetTranslation->setNode($node));

		return $node;
	}

	/**
	 * @return Locale
	 */
	protected function getNodeLocale() {
		return $this->converter->convert($this->locales->getSystemLocale(), Locale::class);
	}


}
