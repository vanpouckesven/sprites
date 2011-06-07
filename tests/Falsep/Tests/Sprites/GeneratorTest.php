<?php

/*
 * This file is part of the Sprites package.
 *
 * (c) Pierre Minnieur <pierre@falsep.com>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Falsep\Tests\Sprites;

use Falsep\Sprites\Configuration,
    Falsep\Sprites\Generator,
    Falsep\Sprites\Processor\DynamicProcessor,
    Falsep\Sprites\Test\SpritesTestCase;

class GeneratorTest extends SpritesTestCase
{
    public function testDynamicGeneration()
    {
        $configuration = new Configuration();
        $configuration->setImagine($this->getImagine());
        $configuration->setImage(sprintf('%s/flags.png', $this->path));
        $configuration->setStylesheet(sprintf('%s/flags.css', $this->path));
        $configuration->getFinder()->name('*.png')->in(__DIR__.'/Fixtures/flags');

        $processor = new DynamicProcessor();
        $generator = new Generator(array($configuration), array($processor));
        $generator->generate();

        $sprite = $config->getImagine()->open($config->getImage());
        $this->assertEquals(161, $sprite->getSize()->getWidth());
        $this->assertEquals(11, $sprite->getSize()->getHeight());

        // @todo make use of $this->assertImageEquals();
    }

    public function testFixedGeneration()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}