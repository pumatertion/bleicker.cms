<?php

namespace Bleicker\Cms\TypeConverter\Node;

use Bleicker\Converter\TypeConverter\TypeConverterInterface;
use Bleicker\Framework\Utility\Arrays;
use Bleicker\NodeTypes\Site;

/**
 * Class SiteTypeConverter
 *
 * @package Bleicker\Cms\TypeConverter\Node
 */
class SiteTypeConverter implements TypeConverterInterface {

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
		$node = new Site();
		$node->setTitle(Arrays::getValueByPath($source, 'title'));
		return $node;
	}
}
