<?php

/*
 * This file is part of the Sprites package.
 *
 * (c) Pierre Minnieur <pierre@falsep.com>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Falsep\Sprites;

use Falsep\Sprites\Processor\ProcessorInterface;

class Generator
{
    /**
     * @var array
     */
    private $configs = array();

    /**
     * Constructor.
     *
     * @param array $configs (optional) An array of Configuration instances
     * @param array $processors (optional) An array of ProcessorInterface instances
     * @return void
     */
    public function __construct(array $configs = array(), array $processors = array())
    {
        $this->setConfigurations($configs);

        foreach ($processors as $processor) {
            $this->addProcessor($processor);
        }
    }

    /**
     * Adds a Configuration instance.
     *
     * @param \Falsep\Sprites\Configuration $config The Configuration instance
     * @return void
     */
    public function addConfiguration(Configuration $config)
    {
        $this->configs[] = $config;
    }

    /**
     * Returns an array of Configuration instances.
     *
     * @return array
     */
    public function getConfigurations()
    {
        return $this->configs;
    }

    /**
     * Sets the Configuration instances.
     *
     * @return array
     */
    public function setConfigurations(array $configs)
    {
        $this->configs = $configs;
    }

    /**
     * Adds a ProcesserInterface instance.
     *
     * @param ProcessorInterface $processor The ProcessorInterface instance
     * @return void
     */
    public function addProcessor(ProcessorInterface $processor)
    {
        $this->processors[$processor->getName()] = $processor;
    }

    /**
     * Returns a ProcessorInterface instance.
     *
     * @param string $name The ProcessorInterface name
     * @return ProcessorInterface
     *
     * @throws \InvalidArgumentException
     */
    public function getProcessor($name)
    {
        if (!isset($this->processors[$name])) {
            throw new \InvalidArgumentException(sprintf('Processor "%s" does not exist.', $name));
        }

        return $this->processors[$name];
    }

    /**
     * Returns an array of ProcessorInterface instances.
     *
     * @return array
     */
    public function getProcessors()
    {
        return $this->processors;
    }

    /**
     * Checks if a ProcessorInterface instance exists.
     *
     * @param string $name The ProcessorInterface name
     * @return boolean
     */
    public function hasProcessor($name)
    {
        return isset($this->processors[$name]);
    }

    /**
     * Processes each Configuration instance w/ a ProcessorInterface instance.
     *
     * @return void
     */
    public function generate()
    {
        foreach ($this->configs as $config) {
            $processor = $this->getProcessor($config->getProcessor());
            $processor->process($config);
        }
    }
}
