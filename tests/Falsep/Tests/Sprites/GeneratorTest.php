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

use Imagine;

use Falsep\Sprites\Generator;

class GeneratorTest extends Imagine\Test\ImagineTestCase
{
    public function setUp()
    {
        $this->path = sys_get_temp_dir().'/falsep/sprites';

        Generator::createDirectory($this->path);
    }

    public function tearDown()
    {
        self::clearDirectory($this->path);
    }

    public function testGenerate()
    {
        $imagine = Generator::getImagineDriver();
        $generator = new Generator($imagine);

        $targetImage = sprintf('%s/flags.png', $this->path);
        $targetStylesheet = sprintf('%s/flags.css', $this->path);
        $cssSelector = '.flag.';

        $generator->getFinder()->name('*.png')->in(__DIR__.'/Fixtures/flags');
        $generator->generate($targetImage, $targetStylesheet, $cssSelector);

        $sprite = $imagine->open($targetImage);
        $this->assertEquals(161, $sprite->getSize()->getWidth());
        $this->assertEquals(11, $sprite->getSize()->getHeight());

        // @todo make use of $this->assertImageEquals();
    }

    static public function clearDirectory($directory)
    {
        if (!is_dir($directory)) {
            return;
        }

        $fp = opendir($directory);
        while (false !== $file = readdir($fp)) {
            if (!in_array($file, array('.', '..')))
            {
                if (is_link($directory.'/'.$file)) {
                    unlink($directory.'/'.$file);
                } else if (is_dir($directory.'/'.$file)) {
                    self::clearDirectory($directory.'/'.$file);
                    rmdir($directory.'/'.$file);
                } else {
                    unlink($directory.'/'.$file);
                }
            }
        }

        closedir($fp);
    }
}