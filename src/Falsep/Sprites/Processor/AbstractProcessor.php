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
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        preg_match('/\\\\(\w+?)?(Processor)?$/i', get_class($this), $matches);

        return strtolower($matches[1]);
    }

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
    protected function createDirectory($paths)
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
}
