<?php

use Bleicker\Cms\TypeConverter\Node\GridElementTypeConverter;
use Bleicker\Cms\TypeConverter\Node\GridTypeConverter;
use Bleicker\Cms\TypeConverter\Node\HeadlineTypeConverter;
use Bleicker\Cms\TypeConverter\Node\PageTypeConverter;
use Bleicker\Cms\TypeConverter\Node\SiteTypeConverter;
use Bleicker\Cms\TypeConverter\Node\TextTypeConverter;
use Bleicker\Converter\TypeConverter\FloatTypeConverter;
use Bleicker\Converter\TypeConverter\IntegerTypeConverter;
use Bleicker\Converter\TypeConverter\StringTypeConverter;
use Bleicker\Framework\Converter\JsonApplicationRequestConverter;
use Bleicker\Framework\Converter\WellformedApplicationRequestConverter;

IntegerTypeConverter::register();
FloatTypeConverter::register();
StringTypeConverter::register();
WellformedApplicationRequestConverter::register();
JsonApplicationRequestConverter::register();
SiteTypeConverter::register();
PageTypeConverter::register();
HeadlineTypeConverter::register();
TextTypeConverter::register();
GridTypeConverter::register();
GridElementTypeConverter::register();
