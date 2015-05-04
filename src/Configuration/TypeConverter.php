<?php

use Bleicker\Cms\TypeConverter\Node\ColumnTypeConverter;
use Bleicker\Cms\TypeConverter\Node\HeadlineTypeConverter;
use Bleicker\Cms\TypeConverter\Node\MultiColumnTypeConverter;
use Bleicker\Cms\TypeConverter\Node\PageTypeConverter;
use Bleicker\Cms\TypeConverter\Node\SiteTypeConverter;
use Bleicker\Cms\TypeConverter\Node\TextTypeConverter;
use Bleicker\Converter\Converter;
use Bleicker\Converter\TypeConverter\FloatTypeConverter;
use Bleicker\Converter\TypeConverter\IntegerTypeConverter;
use Bleicker\Converter\TypeConverter\StringTypeConverter;
use Bleicker\Framework\Converter\JsonApplicationRequestConverter;
use Bleicker\Framework\Converter\JsonApplicationRequestConverterInterface;
use Bleicker\Framework\Converter\WellformedApplicationRequestConverter;
use Bleicker\Framework\Converter\WellformedApplicationRequestConverterInterface;
use Bleicker\NodeTypes\Headline;
use Bleicker\NodeTypes\MultiColumn;
use Bleicker\NodeTypes\Page;
use Bleicker\NodeTypes\Site;
use Bleicker\NodeTypes\Text;
use Doctrine\DBAL\Schema\Column;

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

$pageTypeConverter = new PageTypeConverter();
Converter::register(Page::class, $pageTypeConverter);

$headlineTypeConverter = new HeadlineTypeConverter();
Converter::register(Headline::class, $headlineTypeConverter);

$textTypeConverter = new TextTypeConverter();
Converter::register(Text::class, $textTypeConverter);

$multiColumnTypeConverter = new MultiColumnTypeConverter();
Converter::register(MultiColumn::class, $multiColumnTypeConverter);

$columnTypeConverter = new ColumnTypeConverter();
Converter::register(Column::class, $columnTypeConverter);
