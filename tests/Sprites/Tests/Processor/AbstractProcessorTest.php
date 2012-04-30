<?php

/*
 * This file is part of the Sprites package.
 *
 * (c) Pierre Minnieur <pm@pierre-minnieur.de>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Sprites\Tests\Processor;

use Sprites\Test\SpritesTestCase;

class AbstractProcessorTest extends SpritesTestCase
{
    public function testOptions()
    {
        $processor = new TestProcessor();
        $this->assertFalse($processor->getOption('test'));
        $this->assertEquals('bar', $processor->getOption('foo'));

        $processor = new TestProcessor(array('test' => true, 'foo' => 'test'));
        $this->assertTrue($processor->getOption('test'));
        $this->assertEquals('test', $processor->getOption('foo'));

        $processor->setOption('test', false);
        $this->assertFalse($processor->getOption('test'));

        $processor->setOptions(array('foo' => 'bar'));
        $this->assertEquals('bar', $processor->getOption('foo'));
    }
}
