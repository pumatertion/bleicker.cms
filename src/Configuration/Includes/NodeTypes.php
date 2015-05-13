<?php

use Bleicker\Nodes\Configuration\NodeConfiguration;
use Bleicker\Nodes\ContentNodeInterface;
use Bleicker\Nodes\PageNodeInterface;
use Bleicker\NodeTypes\Grid;
use Bleicker\NodeTypes\GridElement;
use Bleicker\NodeTypes\GridElementInterface;
use Bleicker\NodeTypes\Headline;
use Bleicker\NodeTypes\Page;
use Bleicker\NodeTypes\Site;
use Bleicker\NodeTypes\Text;

/** Register Sites and allow child types */
Site::register('site', 'Website', 'The root page of a domain', NodeConfiguration::SITE_GROUP, [PageNodeInterface::class, ContentNodeInterface::class], [GridElementInterface::class]);

/** Register Pages */
Page::register('page', 'Page', 'A simple page', NodeConfiguration::PAGE_GROUP, [PageNodeInterface::class, ContentNodeInterface::class], [GridElementInterface::class]);

/** Register Content */
Headline::register('headline', 'Headline', 'Title and subtitle', NodeConfiguration::CONTENT_GROUP);
Text::register('text', 'Text', 'Just text', NodeConfiguration::CONTENT_GROUP);
Grid::register('grid', 'Grid', 'Grid wich can contain grid-elements', NodeConfiguration::CONTENT_GROUP, [GridElementInterface::class]);
GridElement::register('gridelement', 'Grid-Element', 'A Grid-Element', NodeConfiguration::CONTENT_GROUP, [ContentNodeInterface::class], [GridElementInterface::class]);
