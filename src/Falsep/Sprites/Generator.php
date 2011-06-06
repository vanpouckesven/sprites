<?php

/*
 * This file is part of the Sprites package.
 *
 * (c) Pierre Minnieur <pierre@falsep.com>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Falsep\Sprites;

use Symfony\Component\Finder\Finder;

use Imagine\Gd\Imagine as GdImagine,
    Imagine\Gmagick\Imagine as GmagickImagine,
    Imagine\Imagick\Imagine as ImagickImagine,
    Imagine\ImagineInterface,
    Imagine\Image\Box,
    Imagine\Image\Point;

class Generator
{
    /**
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;

    /**
     * @var \Imagine\ImagineInterface
     */
    protected $imagine;

    /**
     * Constructor.
     *
     * @param \Imagine\ImagineInterface $imagine (optional)
     * @return void
     */
    public function __construct(ImagineInterface $imagine = null)
    {
        $this->imagine = $imagine;
    }

    /**
     * @return \Imagine\ImagineInterface
     */
    public function getImagine()
    {
        if (null === $this->imagine) {
            $this->imagine = self::getImagineDriver();
        }

        return $this->imagine;
    }

    /**
     * @param \Imagine\ImagineInterface $imagine
     * @return void
     */
    public function setImagine(ImagineInterface $imagine)
    {
        $this->imagine = $imagine;
    }

    /**
     * @return \Symfony\Component\Finder\Finder
     */
    public function getFinder()
    {
        if (null === $this->finder) {
            $this->finder = new Finder();
            $this->finder->files();
        }

        return $this->finder;
    }

    /**
     * @param \Symfony\Component\Finder\Finder $finder
     * @return void
     */
    public function setFinder(Finder $finder)
    {
        $this->finder = $finder;
    }

    /**
     * @param string $targetImage
     * @param string $targetStylesheet
     * @param string $cssSelector
     * @param \Closure $cssAsciify (optional)
     * @return void
     *
     * @throws \RuntimeException
     * @throws \RuntimeException
     * @throws \RuntimeException
     *
     * @todo make $cssAsciify a normal callback
     * @todo make css generation a callback
     */
    public function generate($targetImage, $targetStylesheet, $cssSelector, \Closure $cssAsciify = null)
    {
        if (null === $cssAsciify) {
            // @see http://sourcecookbook.com/en/recipes/8/function-to-slugify-strings-in-php
            $cssAsciify = function(\SplFileInfo $file) {
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
            };
        }

        $sprite = $this->imagine->create(new Box(1, 1));
        $pointer = 0;
        $styles = '';
        foreach ($this->getFinder() as $file) {
            $image = $this->imagine->open($file->getRealPath());

            // adjust height if necessary
            $height = $sprite->getSize()->getHeight();
            if ($image->getSize()->getHeight() > $height) {
                $height = $image->getSize()->getHeight();
            }

            // copy&paste into an extended sprite
            $sprite = $this->imagine->create(new Box($sprite->getSize()->getWidth() + $image->getSize()->getWidth(), $height))->paste($sprite, new Point(0, 0));

            // paste image into sprite
            $sprite->paste($image, new Point($pointer, 0));

            // append stylesheet code
            $styles .= sprintf("%s%s{background-position:%dpx 0px}\n", $cssSelector, $cssAsciify($file), $pointer);

            // move horizontal cursor
            $pointer += $image->getSize()->getWidth();
        }

        self::createDirectory(array($targetImage, $targetStylesheet));

        try {
            $sprite->save($targetImage);
        } catch (\RuntimeException $e) {
            throw new \RuntimeException(sprintf('Unable to write file "%s".', $targetImage));
        }

        if (false === @file_put_contents($targetStylesheet, $styles)) {
            throw new \RuntimeException(sprintf('Unable to write file "%s".', $targetStylesheet));
        }
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
    static public function getImagineDriver($driver = null)
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
