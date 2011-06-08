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
     * @param \Falsep\Sprites\Configration The Configuration instance
     * @return void
     */
    function process(Configuration $config);

    /**
     * Returns the name of the Processor instance.
     *
     * @return string
     */
    function getName();

    /**
     * Returns an options.
     *
     * @param string $key The option key.
     * @return void
     *
     * @throws \InvalidArgumentException If the Processor does not support the option
     */
    function getOption($key);

    /**
     * Sets an options.
     *
     * @param string $key The option key.
     * @param mixed $value The option value.
     * @return void
     *
     * @throws \InvalidArgumentException If the Processor does not support the option
     */
    function setOption($key, $value);

    /**
     * Sets an array of options.
     *
     * @param array $options The array of options.
     * @return void
     *
     * @throws \InvalidArgumentException If the Processor does not support an option
     */
    function setOptions(array $options);
}
