<?php

namespace Bleicker\Cms\TypeConverter\Node;

use Bleicker\Converter\AbstractTypeConverter;
use Bleicker\Framework\Utility\Arrays;
use Bleicker\Nodes\Locale;
use Bleicker\Nodes\NodeServiceInterface;
use Bleicker\Nodes\NodeTranslation;
use Bleicker\NodeTypes\Text;
use Bleicker\ObjectManager\ObjectManager;

/**
 * Class TextTypeConverter
 *
 * @package Bleicker\Cms\TypeConverter\Node
 */
class TextTypeConverter extends AbstractTypeConverter {

	/**
	 * @var NodeServiceInterface
	 */
	protected $nodeService;

	public function __construct() {
		parent::__construct();
		$this->nodeService = ObjectManager::get(NodeServiceInterface::class);
	}

	/**
	 * @param array $source
	 * @param string $targetType
	 * @return boolean
	 */
	public static function canConvert($source = NULL, $targetType) {
		if (is_array($source) && $targetType === Text::class) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * @param array $source
	 * @return Text
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
	 * @return Text
	 */
	protected function getNew(array $source) {
		$node = new Text();
		$node->setBody(Arrays::getValueByPath($source, 'body') !== NULL ? : '');
		return $node;
	}

	/**
	 * Returns an updated site mapped with source arguments
	 *
	 * @param array $source
	 * @return Text
	 */
	protected function getUpdated(array $source) {
		if ($this->isLocalizationMode()) {
			return $this->getLocalized($source);
		}

		$nodeId = Arrays::getValueByPath($source, $this->getIdPath());
		Arrays::unsetValueByPath($source, $this->getIdPath());

		/** @var Text $node */
		$node = $this->nodeService->get($nodeId);

		$node->setBody(Arrays::getValueByPath($source, 'body'));

		return $node;
	}

	/**
	 * @param array $source
	 * @return Text
	 */
	protected function getLocalized(array $source) {
		$nodeId = Arrays::getValueByPath($source, $this->getIdPath());
		Arrays::unsetValueByPath($source, $this->getIdPath());

		/** @var Text $node */
		$node = $this->nodeService->get($nodeId);

		$bodyTranslation = new NodeTranslation('body', $this->getNodeLocale(), Arrays::getValueByPath($source, 'body'));
		$this->nodeService->addTranslation($node, $bodyTranslation);

		return $node;
	}

	/**
	 * @return Locale
	 */
	protected function getNodeLocale() {
		return $this->converter->convert($this->locales->getSystemLocale(), Locale::class);
	}

}
