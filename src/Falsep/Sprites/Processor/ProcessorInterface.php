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

interface ProcessorInterface
{
    /**
     * Processes the Configuration instance.
     *
     * @param \Falsep\Sprites\Configration
     * @return void
     */
    function process(Configuration $config);

    /**
     * Returns the name of the Processor instance.
     *
     * @return string
     */
    function getName();
}
