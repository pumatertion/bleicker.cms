<?php

namespace Bleicker\Cms\ViewHelpers\Translation;

use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Translation\LocalesInterface;
use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class PossibleTranslationsViewHelper
 *
 * @package Bleicker\Cms\ViewHelpers\Translation
 */
class PossibleTranslationsViewHelper extends AbstractViewHelper {

	/**
	 * @var LocalesInterface
	 */
	protected $locales;

	public function __construct() {
		$this->locales = ObjectManager::get(LocalesInterface::class);
	}

	/**
	 * @return array
	 */
	public function render() {
		$result = $this->locales->storage();
		return $result;
	}
}
