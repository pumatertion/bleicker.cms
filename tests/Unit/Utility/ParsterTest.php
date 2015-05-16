<?php

namespace Tests\Bleicker\Cms\Unit\Utility;

use Bleicker\Cms\Utility\Parser;
use Tests\Bleicker\Cms\UnitTestCase;

/**
 * Class ParsterTest
 *
 * @package Tests\Bleicker\Cms\Unit\Utility
 */
class ParsterTest extends UnitTestCase {

	/**
	 * @test
	 */
	public function nodeIdParserTest() {
		$prefix = '/nodemanager/';
		$subject = 'This is some text with a node link <node id="12345?foo=bar" foo="bar" whatever="baz">Hello world</node> and text continues ...';
		$expected = 'This is some text with a node link <a href="' . $prefix . '12345?foo=bar" foo="bar" whatever="baz">Hello world</a> and text continues ...';
		$result = Parser::nodeTagIdToPath($subject, Parser::defaultNodeTagToAnchorClosure('/nodemanager/'));
		$this->assertEquals($expected, $result);
	}

	/**
	 * @test
	 */
	public function multiNodeIdParserTest() {
		$prefix = '/nodemanager/';
		$subject = 'This is some text with a node link <node id="1?foo=bar" foo="bar" whatever="baz">Hello world</node> and text continues ...';
		$subject .= 'This is some text with a node link <node id="2?foo=bar" foo="bar" whatever="baz">Hello world</node> and text continues ...';
		$subject .= 'This is some text with a node link <node id="3?foo=bar" foo="bar" whatever="baz">Hello world</node> and text continues ...';
		$expected = 'This is some text with a node link <a href="' . $prefix . '1?foo=bar" foo="bar" whatever="baz">Hello world</a> and text continues ...';
		$expected .= 'This is some text with a node link <a href="' . $prefix . '2?foo=bar" foo="bar" whatever="baz">Hello world</a> and text continues ...';
		$expected .= 'This is some text with a node link <a href="' . $prefix . '3?foo=bar" foo="bar" whatever="baz">Hello world</a> and text continues ...';
		$result = Parser::nodeTagIdToPath($subject, Parser::defaultNodeTagToAnchorClosure('/nodemanager/'));
		$this->assertEquals($expected, $result);
	}
}
