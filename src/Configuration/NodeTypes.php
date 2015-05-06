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

/**
 * Register Sites and allow child types
 */
NodeConfiguration::register(Site::class, 'site', 'Website', 'The root page of a domain', NodeConfiguration::SITE_GROUP, PageNodeInterface::class, ContentNodeInterface::class);

/**
 * Register Pages
 */
NodeConfiguration::register(Page::class, 'page', 'Page', 'A simple page', NodeConfiguration::PAGE_GROUP, PageNodeInterface::class, ContentNodeInterface::class);

/**
 * Register Content
 */
NodeConfiguration::register(Headline::class, 'headline', 'Headline', 'Title and subtitle', NodeConfiguration::CONTENT_GROUP);
NodeConfiguration::register(Text::class, 'text', 'Text', 'Just text', NodeConfiguration::CONTENT_GROUP);
NodeConfiguration::register(Grid::class, 'grid', 'Grid', 'Grid wich can contain grid-elements', NodeConfiguration::CONTENT_GROUP, GridElementInterface::class);
NodeConfiguration::register(GridElement::class, 'gridelement', 'Grid-Element', 'A Grid-Element', NodeConfiguration::CONTENT_GROUP, ContentNodeInterface::class);
