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

use Falsep\Sprites\Configuration;

use Imagine\ImageInterface;

abstract class AbstractProcessor implements ProcessorInterface
{
    /**
     * An array of options.
     *
     * @var array
     */
    protected $options = array();

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        preg_match('/\\\\(\w+?)?(Processor)?$/i', get_class($this), $matches);

        return strtolower($matches[1]);
    }

    /**
     * {@inheritDoc}
     */
    public function getOption($key)
    {
        if (!array_key_exists($key, $this->options)) {
            throw new \InvalidArgumentException(sprintf('The Processor does not support the "%s" option.', $key));
        }

        return $this->options[$key];
    }

    /**
     * {@inheritDoc}
     */
    public function setOption($key, $value)
    {
        if (!array_key_exists($key, $this->options)) {
            throw new \InvalidArgumentException(sprintf('The Processor does not support the "%s" option.', $key));
        }

        $this->options[$key] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function setOptions(array $options)
    {
        $invalid = array();
        $isInvalid = false;
        foreach ($options as $key => $value) {
            if (array_key_exists($key, $this->options)) {
                $this->options[$key] = $value;
            } else {
                $isInvalid = true;
                $invalid[] = $key;
            }
        }

        if ($isInvalid) {
            throw new \InvalidArgumentException(sprintf('The Processor does not support the following options: "%s".', implode('\', \'', $invalid)));
        }
    }

    /**
     * @see http://sourcecookbook.com/en/recipes/8/function-to-slugify-strings-in-php
     */
    protected function asciify(\SplFileInfo $file)
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
     * Creates the given directory.
     *
     * @param array|string $paths An array of directories or a single directory
     * @return void
     *
     * @throws \RuntimeException If a directory cannot be created
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

    /**
     * Parses the CSS rule.
     *
     * @param string $selector The CSS selector
     * @param \SplFileInfo $file The SplFileInfo instance
     * @param integer $pointer The current pointer
     * @return string
     */
    protected function parseCssRule($selector, \SplFileInfo $file, $pointer)
    {
        return sprintf("%s{background-position:%dpx 0px}\n", sprintf($selector, $this->asciify($file)), $pointer);
    }

    /**
     * Saves the image sprite and stylesheet.
     *
     * @param \Falsep\Sprites\Configuration $config The Configuration instance
     * @param \Imagine\ImageInterface $image The ImageInterface instance
     * @param string $styles The CSS stylesheet
     * @return void
     *
     * @throws \RuntimeException If the image sprite could not be saved
     * @throws \RuntimeException If the stylesheet could not be saved
     */
    protected function save(Configuration $config, ImageInterface $image, $styles)
    {
        $this->createDirectory(array($config->getImage(), $config->getStylesheet()));

        try {
            $image->save($config->getImage(), $config->getOptions());
        } catch (\RuntimeException $e) {
            throw new \RuntimeException(sprintf('Unable to write file "%s".', $config->getImage()));
        }

        if (false === @file_put_contents($config->getStylesheet(), $styles)) {
            throw new \RuntimeException(sprintf('Unable to write file "%s".', $config->getStylesheet()));
        }
    }
}
