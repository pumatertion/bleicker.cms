<?php

namespace Bleicker\Cms\Utility;

use Closure;

/**
 * Class Parser
 *
 * @package Bleicker\Cms\Utility
 */
class Parser {

	const NODE_TAG_SEARCH_PATTERN = '~<node.*?id="(.*?)"(.*?)>(.*?)</node>~sm';

	/**
	 * @param string $subject
	 * @param Closure $replace
	 * @return string
	 */
	public static function nodeTagIdToPath($subject, Closure $replace) {
		return preg_replace_callback(static::NODE_TAG_SEARCH_PATTERN, $replace, $subject);
	}

	/**
	 * @param string $hrefPrefix
	 * @return Closure
	 */
	public static function defaultNodeTagToAnchorClosure($hrefPrefix = NULL) {
		return function ($matches) use ($hrefPrefix) {
			return '<a href="' . $hrefPrefix . $matches[1] . '"' . $matches[2] . '>' . $matches[3] . '</a>';
		};
	}
}
