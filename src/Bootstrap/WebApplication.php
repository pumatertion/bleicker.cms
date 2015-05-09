<?php

use Bleicker\Authentication\AuthenticationManager;
use Bleicker\Authentication\AuthenticationManagerInterface;
use Bleicker\Converter\Converter;
use Bleicker\Converter\ConverterInterface;
use Bleicker\FastRouter\Router;
use Bleicker\Framework\Context\Context;
use Bleicker\Nodes\Configuration\NodeTypeConfigurations;
use Bleicker\Nodes\Configuration\NodeTypeConfigurationsInterface;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Routing\RouterInterface;
use Bleicker\Token\TokenManager;
use Bleicker\Token\TokenManagerInterface;

include __DIR__ . '/../../vendor/autoload.php';
include __DIR__ . '/../Configuration/Essentials/Secrets.php';

/**
 * Register token manager
 */
ObjectManager::add(TokenManagerInterface::class, TokenManager::class);

/**
 * Register authentication manager
 */
ObjectManager::add(AuthenticationManagerInterface::class, AuthenticationManager::class);

/**
 * Register router
 */
ObjectManager::add(RouterInterface::class, Router::getInstance(__DIR__ . '/../../cache/routing/route.cache.php', Context::isProduction() ? FALSE : TRUE));

/**
 * Register basic type converters
 */
ObjectManager::add(ConverterInterface::class, Converter::class);

/**
 * Register nodetype configuration
 */
ObjectManager::add(NodeTypeConfigurationsInterface::class, NodeTypeConfigurations::class);

include __DIR__ . '/../Configuration/Locales.php';
include __DIR__ . '/../Configuration/Cache.php';
include __DIR__ . '/../Configuration/Routes.php';
include __DIR__ . '/../Configuration/Persistence.php';
include __DIR__ . '/../Configuration/TypeConverter.php';
include __DIR__ . '/../Configuration/NodeTypes.php';
include __DIR__ . '/../Configuration/View.php';
