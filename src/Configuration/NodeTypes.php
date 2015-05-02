<?php

use Bleicker\NodeTypes\Column;
use Bleicker\NodeTypes\Headline;
use Bleicker\NodeTypes\MultiColumn;
use Bleicker\NodeTypes\Page;
use Bleicker\NodeTypes\Site;
use Bleicker\NodeTypes\Text;
use Bleicker\Registry\Registry;

Registry::set('nodetypes.site', Site::class);
Registry::set('nodetypes.page', Page::class);
Registry::set('nodetypes.headline', Headline::class);
Registry::set('nodetypes.text', Text::class);
Registry::set('nodetypes.multicolumn', MultiColumn::class);
Registry::set('nodetypes.column', Column::class);