<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2c6b9bbd002f33a28c1a4ccc0f69992b
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'StMartinWof\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'StMartinWof\\' => 
        array (
            0 => __DIR__ . '/../..' . '/class',
        ),
    );

    public static $classMap = array (
        'AltoRouter' => __DIR__ . '/..' . '/altorouter/altorouter/AltoRouter.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2c6b9bbd002f33a28c1a4ccc0f69992b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2c6b9bbd002f33a28c1a4ccc0f69992b::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit2c6b9bbd002f33a28c1a4ccc0f69992b::$classMap;

        }, null, ClassLoader::class);
    }
}
