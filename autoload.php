<?php

/*
 * This file is part of the Sprites package.
 *
 * (c) Pierre Minnieur <pierre@falsep.com>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

require_once __DIR__.'/src/vendor/Symfony/Component/ClassLoader/UniversalClassLoader.php';

$loader = new \Symfony\Component\ClassLoader\UniversalClassLoader();
$loader->registerNamespaces(array(
    'Falsep' => array(__DIR__.'/src', __DIR__.'/tests'),
    'Imagine' => __DIR__.'/src/vendor/Imagine/lib',
    'Symfony' => __DIR__.'/src/vendor',
));
$loader->register();
