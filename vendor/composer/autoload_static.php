<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1a091ab27be82d8f5fe4784ec77e2601
{
    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'RRZE\\CMSinfo\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'RRZE\\CMSinfo\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static $classMap = array (
        'RRZE\\CMSinfo\\Exception' => __DIR__ . '/../..' . '/includes/Exception.php',
        'RRZE\\CMSinfo\\Main' => __DIR__ . '/../..' . '/includes/Main.php',
        'RRZE\\CMSinfo\\Settings' => __DIR__ . '/../..' . '/includes/Settings.php',
        'RRZE\\CMSinfo\\Shortcode' => __DIR__ . '/../..' . '/includes/Shortcode.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1a091ab27be82d8f5fe4784ec77e2601::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1a091ab27be82d8f5fe4784ec77e2601::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit1a091ab27be82d8f5fe4784ec77e2601::$classMap;

        }, null, ClassLoader::class);
    }
}
