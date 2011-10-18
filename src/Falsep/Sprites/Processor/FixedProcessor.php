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

class FixedProcessor extends AbstractProcessor
{
    /**
     * Constructor.
     *
     * @param array $options (optional) An array of options.
     * @return void
     */
    public function __construct(array $options = array())
    {
        $this->options = array(
            'resize' => false,
        );

        $this->setOptions($options);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'fixed';
    }

    /**
     * {@inheritDoc}
     */
    public function process(Configuration $config)
    {
        $sprite = $config->getImagine()->create(new Box(ceil($config->getWidth() * iterator_count($config->getFinder())), 1), $config->getColor());
        $pointer = 0;
        $styles = '';
        foreach ($config->getFinder() as $file) {
            $image = $config->getImagine()->open($file->getRealPath());

            // resize if image exceeds fixed with
            if (true === $this->getOption('resize') && $image->getSize()->getWidth() > $config->getWidth()) {
                $image->resize(new Box($config->getWidth(), round($image->getSize()->getHeight() / $image->getSize()->getWidth() * $config->getWidth())));
            }

            // adjust height if necessary
            if ($image->getSize()->getHeight() > $sprite->getSize()->getHeight()) {
                // copy&paste into an extended sprite
                $sprite = $config->getImagine()->create(new Box($sprite->getSize()->getWidth(), $image->getSize()->getHeight()), $config->getColor())->paste($sprite, new Point(0, 0));
            }

            // paste image into sprite
            $sprite->paste($image, new Point($pointer, 0));

            // append stylesheet code
            $styles .= $this->parseSelector($config->getSelector(), $file, $pointer);

            // move horizontal cursor
            $pointer += $config->getWidth();
        }

        $this->save($config, $sprite, $styles);
    }
}
