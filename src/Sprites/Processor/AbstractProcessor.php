<?php

/*
 * This file is part of the Sprites package.
 *
 * (c) Pierre Minnieur <pm@pierre-minnieur.de>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Sprites\Processor;

use Sprites\Configuration;

use Phly\Mustache\Mustache;

use Imagine\Image\ImageInterface;

abstract class AbstractProcessor implements ProcessorInterface
{
    /**
     * A Mustache instance.
     *
     * @var Mustache
     */
    protected $mustache;

    /**
     * An array of options.
     *
     * @var array
     */
    protected $options = array();

    /**
     * {@inheritDoc}
     */
    public function getOption($key)
    {
        if (!array_key_exists($key, $this->options)) {
            // @codeCoverageIgnoreStart
            throw new \InvalidArgumentException(sprintf('The Processor does not support the "%s" option.', $key));
            // @codeCoverageIgnoreEnd
        }

        return $this->options[$key];
    }

    /**
     * {@inheritDoc}
     */
    public function setOption($key, $value)
    {
        if (!array_key_exists($key, $this->options)) {
            // @codeCoverageIgnoreStart
            throw new \InvalidArgumentException(sprintf('The Processor does not support the "%s" option.', $key));
            // @codeCoverageIgnoreEnd
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
            // @codeCoverageIgnoreStart
            throw new \InvalidArgumentException(sprintf('The Processor does not support the following options: "%s".', implode('\', \'', $invalid)));
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Parses the CSS selector.
     *
     * @param string $selector The CSS selector
     * @param \SplFileInfo $file The SplFileInfo instance
     * @param integer $pointer The current pointer
     * @return string
     */
    protected function parseSelector($selector, \SplFileInfo $file, $pointer)
    {
        return $this->getMustache()->render($selector, array(
            'filename' => $this->asciify($file),
            'pointer' => $pointer
        ));
    }

    /**
     * Returns a Mustache instance.
     *
     * @return Mustache
     */
    protected function getMustache()
    {
        if (null === $this->mustache) {
            $this->mustache = new Mustache();
        }

        return $this->mustache;
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
            // @codeCoverageIgnoreStart
            throw new \RuntimeException(sprintf('Unable to ASCIIfiy "%s".', $file->getFilename()));
            // @codeCoverageIgnoreEnd
        }

        return $ascii;
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
            // @codeCoverageIgnoreStart
            throw new \RuntimeException(sprintf('Unable to write file "%s".', $config->getImage()));
            // @codeCoverageIgnoreEnd

        }

        if (false === @file_put_contents($config->getStylesheet(), $styles)) {
            // @codeCoverageIgnoreStart
            throw new \RuntimeException(sprintf('Unable to write file "%s".', $config->getStylesheet()));
            // @codeCoverageIgnoreEnd
        }
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
                // @codeCoverageIgnoreStart
                throw new \RuntimeException(sprintf('Unable to create directory "%s".', $dir));
                // @codeCoverageIgnoreEnd
            }
        }
    }
}
