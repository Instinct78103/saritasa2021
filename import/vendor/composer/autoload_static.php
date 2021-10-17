<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5a58e6bb5cc997911107f5ca480fed01
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Component\\OptionsResolver\\' => 34,
        ),
        'A' => 
        array (
            'Akeneo\\Component\\SpreadsheetParser\\' => 35,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Component\\OptionsResolver\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/options-resolver',
        ),
        'Akeneo\\Component\\SpreadsheetParser\\' => 
        array (
            0 => __DIR__ . '/..' . '/akeneo-labs/spreadsheet-parser/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5a58e6bb5cc997911107f5ca480fed01::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5a58e6bb5cc997911107f5ca480fed01::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
