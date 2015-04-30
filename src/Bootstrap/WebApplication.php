<?php

use Bleicker\Authentication\AuthenticationManager;
use Bleicker\Authentication\AuthenticationManagerInterface;
use Bleicker\Converter\Converter;
use Bleicker\Converter\ConverterInterface;
use Bleicker\Converter\TypeConverter\FloatTypeConverter;
use Bleicker\Converter\TypeConverter\IntegerTypeConverter;
use Bleicker\Converter\TypeConverter\StringTypeConverter;
use Bleicker\FastRouter\Router;
use Bleicker\Framework\Context\Context;
use Bleicker\Framework\Http\Handler;
use Bleicker\Framework\Http\Request;
use Bleicker\Framework\Security\AccessVoter;
use Bleicker\Framework\Security\AccessVoterInterface;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Request\HandlerInterface;
use Bleicker\Request\MainRequestInterface;
use Bleicker\Response\Http\Response;
use Bleicker\Response\MainResponseInterface;
use Bleicker\Routing\RouterInterface;
use Bleicker\Session\Session;
use Bleicker\Session\SessionInterface;
use Bleicker\Token\TokenManager;
use Bleicker\Token\TokenManagerInterface;

include __DIR__ . '/../../vendor/autoload.php';
include __DIR__ . '/../Configuration/Essentials/Constants.php';
include __DIR__ . '/../Configuration/Essentials/Secrets.php';

/**
 * Register the incoming main request
 */
$request = Request::createFromGlobals();
ObjectManager::register(MainRequestInterface::class, $request);

/**
 * Register the session
 */
$session = new Session();
$request->setSession($session);
ObjectManager::register(SessionInterface::class, $session);

/**
 * Register the outgoing main response
 */
$response = new Response();
ObjectManager::register(MainResponseInterface::class, $response);

/**
 * Register token manager
 */
$tokenManager = new TokenManager();
ObjectManager::register(TokenManagerInterface::class, $tokenManager);

/**
 * Register authentication manager
 */
$authenticationManager = new AuthenticationManager();
ObjectManager::register(AuthenticationManagerInterface::class, $authenticationManager);

/**
 * Register router
 */
$router = Router::getInstance(ROOT_DIRECTORY . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'route.cache.php', Context::isProduction() ? FALSE : TRUE);
ObjectManager::register(RouterInterface::class, $router);

/**
 * Register access voter
 */
$accessVoter = new AccessVoter();
ObjectManager::register(AccessVoterInterface::class, $accessVoter);

/**
 * Register http handler
 */
$requestHandler = new Handler();
ObjectManager::register(HandlerInterface::class, $requestHandler);

/**
 * Register basic type converters
 */
$converter = new Converter();
ObjectManager::register(ConverterInterface::class, $converter);

$integerTypeConverter = new IntegerTypeConverter();
Converter::register(IntegerTypeConverter::class, $integerTypeConverter);

$floatTypeConverter = new FloatTypeConverter();
Converter::register(FloatTypeConverter::class, $floatTypeConverter);

$stringTypeConverter = new StringTypeConverter();
Converter::register(StringTypeConverter::class, $stringTypeConverter);

include __DIR__ . '/../Configuration/Cache.php';
include __DIR__ . '/../Configuration/Routes.php';
include __DIR__ . '/../Configuration/Persistence.php';