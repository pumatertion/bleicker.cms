<?php

use Bleicker\NodeTypes\Column;
use Bleicker\NodeTypes\Headline;
use Bleicker\NodeTypes\MultiColumn;
use Bleicker\NodeTypes\Page;
use Bleicker\NodeTypes\Site;
use Bleicker\NodeTypes\Text;
use Bleicker\Registry\Registry;

/**
 * Register Sites
 */
Registry::set('nodetypes.site.class', Site::class);
Registry::set('nodetypes.site.group', 'Sites');
Registry::set('nodetypes.site.label', 'Site');

/**
 * Register Pages
 */
Registry::set('nodetypes.page.class', Page::class);
Registry::set('nodetypes.page.group', 'Pages');
Registry::set('nodetypes.page.label', 'Page');

/**
 * Register Content
 */
Registry::set('nodetypes.headline.class', Headline::class);
Registry::set('nodetypes.headline.group', 'Content');
Registry::set('nodetypes.headline.label', 'Headline');

Registry::set('nodetypes.text.class', Text::class);
Registry::set('nodetypes.text.group', 'Content');
Registry::set('nodetypes.text.label', 'Text');

Registry::set('nodetypes.multicolumn.class', MultiColumn::class);
Registry::set('nodetypes.multicolumn.group', 'Content');
Registry::set('nodetypes.multicolumn.label', 'Grid');

Registry::set('nodetypes.column.class', Column::class);
Registry::set('nodetypes.column.group', 'Content');
Registry::set('nodetypes.column.label', 'Grid element');
