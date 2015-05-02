<?php

use Bleicker\Cms\TypeConverter\Node\SiteTypeConverter;
use Bleicker\Converter\Converter;
use Bleicker\Converter\TypeConverter\FloatTypeConverter;
use Bleicker\Converter\TypeConverter\IntegerTypeConverter;
use Bleicker\Converter\TypeConverter\StringTypeConverter;
use Bleicker\Framework\Converter\JsonApplicationRequestConverter;
use Bleicker\Framework\Converter\JsonApplicationRequestConverterInterface;
use Bleicker\Framework\Converter\WellformedApplicationRequestConverter;
use Bleicker\Framework\Converter\WellformedApplicationRequestConverterInterface;
use Bleicker\NodeTypes\Site;

$integerTypeConverter = new IntegerTypeConverter();
Converter::register(IntegerTypeConverter::class, $integerTypeConverter);

$floatTypeConverter = new FloatTypeConverter();
Converter::register(FloatTypeConverter::class, $floatTypeConverter);

$stringTypeConverter = new StringTypeConverter();
Converter::register(StringTypeConverter::class, $stringTypeConverter);

$wellformedRequestTypeConverter = new WellformedApplicationRequestConverter();
Converter::register(WellformedApplicationRequestConverterInterface::class, $wellformedRequestTypeConverter);

$jsonRequestTypeConverter = new JsonApplicationRequestConverter();
Converter::register(JsonApplicationRequestConverterInterface::class, $jsonRequestTypeConverter);

$siteTypeConverter = new SiteTypeConverter();
Converter::register(Site::class, $siteTypeConverter);