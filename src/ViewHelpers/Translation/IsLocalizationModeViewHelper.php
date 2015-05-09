<?php

namespace Bleicker\Cms\ViewHelpers\Translation;

use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Translation\LocalesInterface;
use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class IsLocalizationModeViewHelper
 *
 * @package Bleicker\Cms\ViewHelpers\Translation
 */
class IsLocalizationModeViewHelper extends AbstractViewHelper {

	/**
	 * @var LocalesInterface
	 */
	protected $locales;

	public function __construct() {
		$this->locales = ObjectManager::get(LocalesInterface::class);
	}

	/**
	 * @return boolean
	 */
	public function render() {
		return (string)$this->locales->getSystemLocale() !== (string)$this->locales->getDefault();
	}
}
