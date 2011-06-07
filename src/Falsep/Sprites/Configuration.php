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

use Imagine\ImagineInterface;

class Configuration
{
    /**
     * The ImagineInterface instance.
     *
     * @var \Imagine\ImagineInterface
     */
    private $imagine;

    /**
     * The Finder instance.
     *
     * @var \Symfony\Component\Finder\Finder
     */
    private $finder;

    /**
     * The target image path.
     *
     * @var string
     */
    private $image;

    /**
     * The fixed width per image.
     *
     * @var integer
     */
    private $width;

    /**
     * The fixed height per image.
     *
     * @var integer
     */
    private $height;

    /**
     * The target stylesheet path.
     *
     * @var string
     */
    private $stylesheet;

    /**
     * The CSS selector callable.
     *
     * @var callable
     */
    private $selector;

    /**
     * Returns the ImagineInterface instance.
     *
     * @return \Imagine\ImagineInterface
     */
    public function getImagine()
    {
        return $this->imagine;
    }

    /**
     * Sets the ImagineInterface instance.
     *
     * @param \Imagine\ImagineInterface $imagine The ImagineInterface instance
     * @return void
     */
    public function setImagine(ImagineInterface $imagine)
    {
        $this->imagine = $imagine;
    }

    /**
     * Returns the Finder instance.
     *
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
     * Sets the Finder instance.
     *
     * @param \Symfony\Component\Finder\Finder $finder The Finder instance
     * @return void
     */
    public function setFinder(Finder $finder)
    {
        $this->finder = $finder;
    }

    /**
     * Returns the target image path.
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Sets the target image path.
     *
     * @param string $path The target image path
     * @return void
     */
    public function setImage($path)
    {
        $this->image = $path;
    }

    /**
     * Returns the fixed width per image.
     *
     * @return integer
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Sets the fixed width per image.
     *
     * @param integer $width The fixed with per image
     * @return void
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * Returns the fixed height per image.
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Sets the fixed height per image.
     *
     * @return integer $height The fixed height per image
     * @return void
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * Returns the target stylesheet path.
     *
     * @return string
     */
    public function getStylesheet()
    {
        return $this->stylesheet;
    }

    /**
     * Sets the target stylesheet path.
     *
     * @param string $path The target stylesheet path
     * @return void
     */
    public function setStylesheet($path)
    {
        $this->stylesheet = $stylesheet;
    }

    /**
     * Returns the CSS selector callable.
     *
     * @return callable
     */
    public function getSelector()
    {
        return $this->selector;
    }

    /**
     * Sets the CSS selector callable.
     *
     * @param callable $selector The CSS selector callable
     * @return void
     */
    public function setSelector($selector)
    {
        $this->selector = $selector;
    }
}