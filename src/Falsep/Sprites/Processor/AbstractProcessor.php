<?php

/*
 * This file is part of the Sprites package.
 *
 * (c) Pierre Minnieur <pierre@falsep.com>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Falsep\Sprites\Processor;

abstract class AbstractProcessor implements ProcessorInterface
{
    abstract public function process();

    /**
     * @see http://sourcecookbook.com/en/recipes/8/function-to-slugify-strings-in-php
     */
    static public function asciify(\SplFileInfo $file)
    {
        // replace non letter or digits by - and trim -
        $ascii = trim(preg_replace('~[^\\pL\d]+~u', '-', $file->getBasename(pathinfo($file->getBasename(), \PATHINFO_EXTENSION))), '-');

        // transliterate
        if (function_exists('iconv')) {
            $ascii = iconv('utf-8', 'us-ascii//TRANSLIT', $ascii);
        }

        // lowercase and remove unwanted characters
        $ascii = preg_replace('~[^-\w]+~', '', strtolower($ascii));

        if (empty($ascii)) {
            throw new \RuntimeException(sprintf('Unable to ASCIIfiy "%s".', $file->getFilename()));
        }

        return $ascii;
    }

    /**
     * @param array|string $paths
     * @return void
     *
     * @throws \RuntimeException
     */
    static public function createDirectory($paths)
    {
        if (!is_array($paths)) {
            $paths = array($paths);
        }

        foreach ($paths as $path) {
            if (!is_dir($dir = dirname($path)) && false === @mkdir($dir, 0777, true)) {
                throw new \RuntimeException(sprintf('Unable to create directory "%s".', $dir));
            }
        }
    }

    /**
     * Returns an ImagineInterface instance.
     *
     * @param string $driver (optional)
     * @return \Imagine\ImagineInterface
     */
    static public function getImagine($driver = null)
    {
        if (null === $driver) {
            switch (true) {
                case function_exists('gd_info'):
                    $driver = 'gd';
                    break;
                case class_exists('Gmagick'):
                    $driver = 'gmagick';
                    break;
                case class_exists('Imagick'):
                    $driver = 'imagick';
                    break;
            }
        }

        if (!in_array($driver, array('gd', 'gmagick', 'imagick'))) {
            throw new \RuntimeException('Unable to determine Imagine driver.');
        }

        switch (strtolower($driver)) {
            case 'gd':
                return new GdImagine();
            case 'gmagick':
                return new GmagickImagine();
            case 'imagick':
                return new ImagickImagine();
        }
    }
}
