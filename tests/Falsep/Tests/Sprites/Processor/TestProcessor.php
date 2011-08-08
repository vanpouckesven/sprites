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
    Falsep\Sprites\Processor\AbstractProcessor;

class TestProcessor extends AbstractProcessor
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
            'test' => false,
            'foo' => 'bar',
        );

        $this->setOptions($options);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'test';
    }

    /**
     * Processes the Configuration instance.
     *
     * @param \Falsep\Sprites\Configration The Configuration instance
     * @return void
     */
    public function process(Configuration $config)
    {
    }
}
