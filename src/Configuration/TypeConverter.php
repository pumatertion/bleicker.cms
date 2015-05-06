<?php

use Bleicker\Cms\TypeConverter\Node\GridElementTypeConverter;
use Bleicker\Cms\TypeConverter\Node\GridTypeConverter;
use Bleicker\Cms\TypeConverter\Node\HeadlineTypeConverter;
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
use Bleicker\NodeTypes\Grid;
use Bleicker\NodeTypes\GridElement;
use Bleicker\NodeTypes\Headline;
use Bleicker\NodeTypes\Page;
use Bleicker\NodeTypes\Site;
use Bleicker\NodeTypes\Text;

Converter::register(IntegerTypeConverter::class, new IntegerTypeConverter());
Converter::register(FloatTypeConverter::class, new FloatTypeConverter());
Converter::register(StringTypeConverter::class, new StringTypeConverter());
Converter::register(WellformedApplicationRequestConverterInterface::class, new WellformedApplicationRequestConverter());
Converter::register(JsonApplicationRequestConverterInterface::class, new JsonApplicationRequestConverter());
Converter::register(Site::class, new SiteTypeConverter());
Converter::register(Page::class, new PageTypeConverter());
Converter::register(Headline::class, new HeadlineTypeConverter());
Converter::register(Text::class, new TextTypeConverter());
Converter::register(Grid::class, new GridTypeConverter());
Converter::register(GridElement::class, new GridElementTypeConverter());
