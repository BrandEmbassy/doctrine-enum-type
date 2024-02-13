<?php declare(strict_types = 1);

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\Config\RectorConfig;


$rectorConfigBuilder = RectorConfig::configure();
$defaultRectorConfigurationSetup = require 'vendor/brandembassy/coding-standard/default-rector.php';

$defaultSkipList = $defaultRectorConfigurationSetup($rectorConfigBuilder);

$skipList = array_merge($defaultSkipList, [

]);

$rectorConfigBuilder
    ->withPHPStanConfigs([__DIR__ . '/phpstan.neon'])
    ->withCache('./var/rector', FileCacheStorage::class)
    ->withPaths([
        __DIR__ . '/src',
    ])
    ->withSkip($skipList);

return $rectorConfigBuilder;