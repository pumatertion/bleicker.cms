<?php

use Bleicker\Cms\TypeConverter\Node\GridElementTypeConverter;
use Bleicker\Cms\TypeConverter\Node\GridTypeConverter;
use Bleicker\Cms\TypeConverter\Node\HeadlineTypeConverter;
use Bleicker\Cms\TypeConverter\Node\ImageTypeConverter;
use Bleicker\Cms\TypeConverter\Node\NodeLocaleTypeConverter;
use Bleicker\Cms\TypeConverter\Node\PageTypeConverter;
use Bleicker\Cms\TypeConverter\Node\SiteTypeConverter;
use Bleicker\Cms\TypeConverter\Node\TextTypeConverter;
use Bleicker\Converter\TypeConverter\FloatTypeConverter;
use Bleicker\Converter\TypeConverter\IntegerTypeConverter;
use Bleicker\Converter\TypeConverter\StringTypeConverter;

IntegerTypeConverter::register();
FloatTypeConverter::register();
StringTypeConverter::register();
SiteTypeConverter::register();
PageTypeConverter::register();
HeadlineTypeConverter::register();
TextTypeConverter::register();
GridTypeConverter::register();
GridElementTypeConverter::register();
NodeLocaleTypeConverter::register();
ImageTypeConverter::register();