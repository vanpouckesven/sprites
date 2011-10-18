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
    Falsep\Sprites\Processor\FixedProcessor,
    Falsep\Sprites\Test\SpritesTestCase;

class GeneratorTest extends SpritesTestCase
{
    public function testGeneration()
    {
        $dynamic = new Configuration();
        $dynamic->setImagine($this->getImagine());
        $dynamic->setColor($this->getColor());
        $dynamic->setImage(sprintf('%s/flags.png', $this->path));
        $dynamic->setStylesheet(sprintf('%s/flags.css', $this->path));
        $dynamic->setSelector(".flag.{{filename}}{background-position:{{pointer}}px 0px}\n");
        $dynamic->getFinder()->name('*.png')->in(__DIR__.'/Fixtures/flags')->sortByName();

        $fixed = new Configuration();
        $fixed->setImagine($this->getImagine());
        $fixed->setColor($this->getColor());
        $fixed->setImage(sprintf('%s/icons.png', $this->path));
        $fixed->setStylesheet(sprintf('%s/icons.css', $this->path));
        $fixed->setSelector(".icon.{{filename}}{background-position:{{pointer}}px 0px}\n");
        $fixed->getFinder()->name('*.png')->in(__DIR__.'/Fixtures/icons')->sortByName();
        $fixed->setWidth(16);

        $generator = new Generator();
        $generator->addConfiguration($dynamic);
        $generator->addConfiguration($fixed);
        $generator->addProcessor(new DynamicProcessor());
        $generator->addProcessor(new FixedProcessor());
        $generator->generate();

        $sprite = $dynamic->getImagine()->open($dynamic->getImage());
        $result = $dynamic->getImagine()->open(__DIR__.'/Fixtures/results/flags.png');
        $this->assertImageEquals($sprite, $result);
        $this->assertFileEquals(__DIR__.'/Fixtures/results/flags.css', $dynamic->getStylesheet());

        $sprite = $fixed->getImagine()->open($fixed->getImage());
        $result = $fixed->getImagine()->open(__DIR__.'/Fixtures/results/icons.png');
        $this->assertImageEquals($result, $sprite);
        $this->assertFileEquals(__DIR__.'/Fixtures/results/icons.css', $fixed->getStylesheet());
    }
}
