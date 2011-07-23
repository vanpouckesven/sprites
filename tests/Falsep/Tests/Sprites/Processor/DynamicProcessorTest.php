<?php

/*
 * This file is part of the Sprites package.
 *
 * (c) Pierre Minnieur <pierre@falsep.com>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Falsep\Tests\Sprites\Processor;

use Falsep\Sprites\Configuration,
    Falsep\Sprites\Processor\DynamicProcessor,
    Falsep\Sprites\Test\SpritesTestCase;

class DynamicProcessorTest extends SpritesTestCase
{
    public function testProcessing()
    {
        $config = new Configuration();
        $config->setImagine($this->getImagine());
        $config->setColor($this->getColor());
        $config->setImage(sprintf('%s/flags.png', $this->path));
        $config->setStylesheet(sprintf('%s/flags.css', $this->path));
        $config->setSelector('.flag.%s');
        $config->getFinder()->name('*.png')->in(__DIR__.'/../Fixtures/flags')->sortByName();

        $processor = new DynamicProcessor();
        $processor->process($config);

        $sprite = $config->getImagine()->open($config->getImage());
        $result = $config->getImagine()->open(__DIR__.'/../Fixtures/results/flags.png');
        $this->assertImageEquals($sprite, $result);
        $this->assertFileEquals(__DIR__.'/../Fixtures/results/flags.css', $config->getStylesheet());
    }
}
