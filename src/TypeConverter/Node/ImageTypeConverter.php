<?php

namespace Bleicker\Cms\TypeConverter\Node;

use Bleicker\Converter\AbstractTypeConverter;
use Bleicker\Framework\Utility\Arrays;
use Bleicker\Nodes\Locale;
use Bleicker\Nodes\NodeService;
use Bleicker\Nodes\NodeServiceInterface;
use Bleicker\Nodes\NodeTranslation;
use Bleicker\NodeTypes\Image;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Registry\Registry;
use Bleicker\Translation\Translation;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class ImageTypeConverter
 *
 * @package Bleicker\Cms\TypeConverter\Node
 */
class ImageTypeConverter extends AbstractTypeConverter {

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
		if (is_array($source) && $targetType === Image::class) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * @param array $source
	 * @return Image
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
	 * @return Image
	 */
	protected function getNew(array $source) {
		$node = new Image();
		$node->setTitle(Arrays::getValueByPath($source, 'title') !== NULL ? : '');
		$node->setAlt(Arrays::getValueByPath($source, 'alt') !== NULL ? : '');
		return $node;
	}

	/**
	 * Returns an updated site mapped with source arguments
	 *
	 * @param array $source
	 * @return Image
	 */
	protected function getUpdated(array $source) {
		if ($this->isLocalizationMode()) {
			return $this->getLocalized($source);
		}

		$nodeId = Arrays::getValueByPath($source, $this->getIdPath());
		Arrays::unsetValueByPath($source, $this->getIdPath());

		/** @var Image $node */
		$node = $this->nodeService->get($nodeId);

		$node->setTitle(Arrays::getValueByPath($source, 'title'));
		$node->setAlt(Arrays::getValueByPath($source, 'alt'));
		$node->setHidden((boolean)Arrays::getValueByPath($source, 'hidden'));

		$resource = Arrays::getValueByPath($source, 'resource');
		if ($resource instanceof UploadedFile) {
			$this->remove($node->getResource());
			$fileName = $this->move($resource);
			$node->setResource($fileName);
		}

		return $node;
	}

	/**
	 * @param array $source
	 * @return Image
	 */
	protected function getLocalized(array $source) {
		$nodeId = Arrays::getValueByPath($source, $this->getIdPath());
		Arrays::unsetValueByPath($source, $this->getIdPath());

		/** @var Image $node */
		$node = $this->nodeService->get($nodeId);

		$titleTranslation = new NodeTranslation('title', $this->getNodeLocale(), Arrays::getValueByPath($source, 'title'));
		$this->nodeService->addTranslation($node, $titleTranslation->setNode($node));

		$altTranslation = new NodeTranslation('alt', $this->getNodeLocale(), Arrays::getValueByPath($source, 'alt'));
		$this->nodeService->addTranslation($node, $altTranslation->setNode($node));

		$resource = Arrays::getValueByPath($source, 'resource');
		if ($resource instanceof UploadedFile) {
			$translation = new Translation('resource', $this->locales->getSystemLocale());
			if ($node->hasTranslation($translation)) {
				$this->remove($node->getTranslation($translation)->getValue());
			}
			$fileName = $this->move($resource);
			$resourceTranslation = new NodeTranslation('resource', $this->getNodeLocale(), $fileName);
			$this->nodeService->addTranslation($node, $resourceTranslation->setNode($node));
		}

		return $node;
	}

	/**
	 * @return Locale
	 */
	protected function getNodeLocale() {
		return $this->converter->convert($this->locales->getSystemLocale(), Locale::class);
	}

	/**
	 * @param string $resource
	 * @return boolean
	 */
	protected function remove($resource = NULL) {
		if ($resource !== NULL || !empty($resource)) {
			$directory = realpath(Registry::get('paths.uploads.default'));
			$resourcePath = $directory . '/' . $resource;
			if (file_exists($resourcePath)) {
				return unlink($resourcePath);
			}
		}
		return FALSE;
	}

	/**
	 * @param UploadedFile $resource
	 * @return string The name of the file after upload
	 */
	protected function move(UploadedFile $resource) {
		$directory = realpath(Registry::get('paths.uploads.default'));
		$movedFile = $resource->move($directory, $resource->getClientOriginalName() . uniqid('_', TRUE) . '.' . $resource->guessExtension());
		return $movedFile->getFilename();
	}
}
