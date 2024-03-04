<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit859543bb8a963eb643ce8ae1c4af9d2a
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'Azad\\Database\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Azad\\Database\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit859543bb8a963eb643ce8ae1c4af9d2a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit859543bb8a963eb643ce8ae1c4af9d2a::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit859543bb8a963eb643ce8ae1c4af9d2a::$classMap;

        }, null, ClassLoader::class);
    }
}
