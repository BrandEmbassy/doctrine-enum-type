<?php declare(strict_types = 1);

use Symplify\EasyCodingStandard\Config\ECSConfig;

$defaultEcsConfigurationSetup = require 'vendor/brandembassy/coding-standard/default-ecs.php';

return static function (ECSConfig $ecsConfig) use ($defaultEcsConfigurationSetup): void {
    $defaultSkipList = $defaultEcsConfigurationSetup($ecsConfig, __DIR__);

    $ecsConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/ecs.php',
    ]);

    $skipList = [
        'BrandEmbassyCodingStandard\Sniffs\NamingConvention\CamelCapsFunctionNameSniff.ScopeNotCamelCaps' => [
            __DIR__ . '/src/EnumType.php',
            __DIR__ . '/tests/Bridges/MyCLabsEnum/MyCLabsEnumBridgeTest.php',
        ],
    ];

    $ecsConfig->skip(array_merge($defaultSkipList, $skipList));
};
