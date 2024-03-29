<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5d9edba1e62adf3c02d8309771e8d02c
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Stomp\\' => 6,
        ),
        'P' => 
        array (
            'PhpAmqpLib\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Stomp\\' => 
        array (
            0 => __DIR__ . '/..' . '/stomp-php/stomp-php/src',
        ),
        'PhpAmqpLib\\' => 
        array (
            0 => __DIR__ . '/..' . '/php-amqplib/php-amqplib/PhpAmqpLib',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5d9edba1e62adf3c02d8309771e8d02c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5d9edba1e62adf3c02d8309771e8d02c::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
