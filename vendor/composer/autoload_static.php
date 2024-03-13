<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit379e1f1372df4339dd4c6a737f236d11
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
            $loader->prefixLengthsPsr4 = ComposerStaticInit379e1f1372df4339dd4c6a737f236d11::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit379e1f1372df4339dd4c6a737f236d11::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit379e1f1372df4339dd4c6a737f236d11::$classMap;

        }, null, ClassLoader::class);
    }
}
