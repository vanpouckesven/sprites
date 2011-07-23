<?php

/*
 * This file is part of the Sprites package.
 *
 * (c) Pierre Minnieur <pierre@falsep.com>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Falsep\Sprites\Test;

use Imagine\Gd,
    Imagine\Gmagick,
    Imagine\Imagick,
    Imagine\Image\Color,
    Imagine\Test\ImagineTestCase;

class SpritesTestCase extends ImagineTestCase
{
    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->path = sys_get_temp_dir().'/falsep/sprites';

        $this->createDirectory($this->path);
    }

    /**
     * {@inheritDoc}
     */
    public function tearDown()
    {
        $this->clearDirectory($this->path);
    }

    /**
     * Creates the given directory.
     *
     * @param string $directory
     * @return void
     *
     * @throws \RuntimeException
     */
    protected function createDirectory($directory)
    {
        if (!is_dir($directory = dirname($directory)) && false === @mkdir($directory, 0777, true)) {
            throw new \RuntimeException(sprintf('Unable to create directory "%s".', $directory));
        }
    }

    /**
     * Clears the given directory.
     *
     * @param string $directory
     * @return void
     */
    protected function clearDirectory($directory)
    {
        if (!is_dir($directory)) {
            return;
        }

        $fp = opendir($directory);
        while (false !== $file = readdir($fp)) {
            if (!in_array($file, array('.', '..'))) {
                if (is_link($directory.'/'.$file)) {
                    unlink($directory.'/'.$file);
                } else if (is_dir($directory.'/'.$file)) {
                    $this->clearDirectory($directory.'/'.$file);
                    rmdir($directory.'/'.$file);
                } else {
                    unlink($directory.'/'.$file);
                }
            }
        }

        closedir($fp);
    }

    /**
     * Returns an ImagineInterface instance.
     *
     * @param string $driver (optional)
     * @return \Imagine\ImagineInterface
     */
    protected function getImagine($driver = null)
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
            throw new \RuntimeException(sprintf('Driver "%s" does not exist.', $driver));
        }

        switch (strtolower($driver)) {
            case 'gd':
                return new Gd\Imagine();
            case 'gmagick':
                return new Gmagick\Imagine();
            case 'imagick':
                return new Imagick\Imagine();
        }
    }

    /**
     * Returns a Color instance.
     *
     * @param array|string $color (optional)
     * @param integer $alpha (optional)
     * @return \Imagine\Image\Color
     */
    protected function getColor($color = array(255, 255, 255), $alpha = 100)
    {
        return new Color($color, $alpha);
    }
}
