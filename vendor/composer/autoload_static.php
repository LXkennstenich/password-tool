<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit34eeddc3f9076080b9cd86fd8a71b4c8
{
    public static $files = array (
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        '2b9d0f43f9552984cfa82fee95491826' => __DIR__ . '/..' . '/sabre/event/lib/coroutine.php',
        'd81bab31d3feb45bfe2f283ea3c8fdf7' => __DIR__ . '/..' . '/sabre/event/lib/Loop/functions.php',
        'a1cce3d26cc15c00fcd0b3354bd72c88' => __DIR__ . '/..' . '/sabre/event/lib/Promise/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'p' => 
        array (
            'phpDocumentor\\Reflection\\' => 25,
        ),
        'W' => 
        array (
            'Webmozart\\Assert\\' => 17,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Component\\Console\\' => 26,
            'Sonata\\GoogleAuthenticator\\' => 27,
            'Sabre\\Event\\' => 12,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
            'Phan\\' => 5,
        ),
        'M' => 
        array (
            'Microsoft\\PhpParser\\' => 20,
        ),
        'G' => 
        array (
            'Google\\Authenticator\\' => 21,
        ),
        'C' => 
        array (
            'Composer\\XdebugHandler\\' => 23,
            'Composer\\Semver\\' => 16,
        ),
        'A' => 
        array (
            'AdvancedJsonRpc\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'phpDocumentor\\Reflection\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpdocumentor/reflection-common/src',
            1 => __DIR__ . '/..' . '/phpdocumentor/reflection-docblock/src',
            2 => __DIR__ . '/..' . '/phpdocumentor/type-resolver/src',
        ),
        'Webmozart\\Assert\\' => 
        array (
            0 => __DIR__ . '/..' . '/webmozart/assert/src',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Component\\Console\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/console',
        ),
        'Sonata\\GoogleAuthenticator\\' => 
        array (
            0 => __DIR__ . '/..' . '/sonata-project/google-authenticator/src',
        ),
        'Sabre\\Event\\' => 
        array (
            0 => __DIR__ . '/..' . '/sabre/event/lib',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'Phan\\' => 
        array (
            0 => __DIR__ . '/..' . '/phan/phan/src/Phan',
        ),
        'Microsoft\\PhpParser\\' => 
        array (
            0 => __DIR__ . '/..' . '/microsoft/tolerant-php-parser/src',
        ),
        'Google\\Authenticator\\' => 
        array (
            0 => __DIR__ . '/..' . '/sonata-project/google-authenticator/src',
        ),
        'Composer\\XdebugHandler\\' => 
        array (
            0 => __DIR__ . '/..' . '/composer/xdebug-handler/src',
        ),
        'Composer\\Semver\\' => 
        array (
            0 => __DIR__ . '/..' . '/composer/semver/src',
        ),
        'AdvancedJsonRpc\\' => 
        array (
            0 => __DIR__ . '/..' . '/felixfbecker/advanced-json-rpc/lib',
        ),
    );

    public static $prefixesPsr0 = array (
        'J' => 
        array (
            'JsonMapper' => 
            array (
                0 => __DIR__ . '/..' . '/netresearch/jsonmapper/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit34eeddc3f9076080b9cd86fd8a71b4c8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit34eeddc3f9076080b9cd86fd8a71b4c8::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit34eeddc3f9076080b9cd86fd8a71b4c8::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
