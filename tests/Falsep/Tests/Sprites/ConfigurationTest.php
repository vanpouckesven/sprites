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

use Falsep\Sprites\Configuration;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testProcessorIsGuessedButCouldBeOverwritten()
    {
        $config = new Configuration;
        $this->assertEquals(Configuration::PROCESSOR_DYNAMIC, $config->getProcessor());

        $config->setWidth(16);
        $this->assertEquals(Configuration::PROCESSOR_FIXED, $config->getProcessor());

        $config->setProcessor('test');
        $this->assertEquals('test', $config->getProcessor());
    }
}
