<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit1476c7e93b773a6d6030abe7f614f51e
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInit1476c7e93b773a6d6030abe7f614f51e', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit1476c7e93b773a6d6030abe7f614f51e', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit1476c7e93b773a6d6030abe7f614f51e::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
