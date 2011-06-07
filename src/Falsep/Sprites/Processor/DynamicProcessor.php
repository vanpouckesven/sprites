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

use Imagine\Image\Box,
    Imagine\Image\Point;

class DynamicProcessor extends AbstractProcessor
{
    /**
     * {@inheritDoc}
     */
    public function process(Configuration $config)
    {
        $sprite = $config->getImagine()->create(new Box(1, 1));
        $pointer = 0;
        $styles = '';
        foreach ($config->getFinder() as $file) {
            $image = $config->getImagine()->open($file->getRealPath());

            // adjust height if necessary
            $height = $sprite->getSize()->getHeight();
            if ($image->getSize()->getHeight() > $height) {
                $height = $image->getSize()->getHeight();
            }

            // copy&paste into an extended sprite
            $sprite = $config->getImagine()->create(new Box($sprite->getSize()->getWidth() + $image->getSize()->getWidth(), $height))->paste($sprite, new Point(0, 0));

            // paste image into sprite
            $sprite->paste($image, new Point($pointer, 0));

            // append stylesheet code
            // @todo use selector callable
            $styles .= sprintf("%s%s{background-position:%dpx 0px}\n", $config->getSelector(), self::asciify($file), $pointer);

            // move horizontal cursor
            $pointer += $image->getSize()->getWidth();
        }

        $this->createDirectory(array($config->getImage(), $config->getStylesheet()));

        try {
            $sprite->save($config->getImage(), $config->getOptions());
        } catch (\RuntimeException $e) {
            throw new \RuntimeException(sprintf('Unable to write file "%s".', $config->getImage()));
        }

        if (false === @file_put_contents($config->getStylesheet(), $styles)) {
            throw new \RuntimeException(sprintf('Unable to write file "%s".', $config->getStylesheet()));
        }
    }
}