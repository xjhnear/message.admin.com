<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit548b7d31ae6683945ea4c505b1ed8cee
{
    public static $files = array (
        'ad155f8f1cf0d418fe49e248db8c661b' => __DIR__ . '/..' . '/react/promise/src/functions_include.php',
        '6b06ce8ccf69c43a60a1e48495a034c9' => __DIR__ . '/..' . '/react/promise-timer/src/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'React\\Stream\\' => 13,
            'React\\Socket\\' => 13,
            'React\\Promise\\Timer\\' => 20,
            'React\\Promise\\' => 14,
            'React\\EventLoop\\' => 16,
            'React\\Dns\\' => 10,
            'React\\Cache\\' => 12,
        ),
        'C' => 
        array (
            'Clue\\React\\Redis\\' => 17,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'React\\Stream\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/stream/src',
        ),
        'React\\Socket\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/socket/src',
        ),
        'React\\Promise\\Timer\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/promise-timer/src',
        ),
        'React\\Promise\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/promise/src',
        ),
        'React\\EventLoop\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/event-loop/src',
        ),
        'React\\Dns\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/dns/src',
        ),
        'React\\Cache\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/cache/src',
        ),
        'Clue\\React\\Redis\\' => 
        array (
            0 => __DIR__ . '/..' . '/clue/redis-react/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'E' => 
        array (
            'Evenement' => 
            array (
                0 => __DIR__ . '/..' . '/evenement/evenement/src',
            ),
        ),
        'C' => 
        array (
            'Clue\\Redis\\Protocol' => 
            array (
                0 => __DIR__ . '/..' . '/clue/redis-protocol/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit548b7d31ae6683945ea4c505b1ed8cee::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit548b7d31ae6683945ea4c505b1ed8cee::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit548b7d31ae6683945ea4c505b1ed8cee::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
