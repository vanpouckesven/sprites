<?php

/*
 * This file is part of the Sprites package.
 *
 * (c) Pierre Minnieur <pierre@falsep.com>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Falsep\Sprites\Command;

use Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Output\OutputInterface;

use Falsep\Sprites\Processor\DynamicProcessor;

class GenerateDynamicSpritesCommand extends GenerateSpritesCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('generate:dynamic')
            ->setDefinition(array(
                new InputArgument('source', InputArgument::REQUIRED),
                new InputArgument('pattern', InputArgument::REQUIRED),
                new InputArgument('image', InputArgument::REQUIRED),
                new InputArgument('stylesheet', InputArgument::REQUIRED),
                new InputArgument('selector', InputArgument::REQUIRED),
                new InputOption('driver', null, InputOption::VALUE_OPTIONAL),
                new InputOption('options', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY),
            ))
            ->setDescription('')
            ->setHelp(<<<EOT

EOT
            )
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $processor = new DynamicProcessor();
        $processor->process($this->getConfiguration($input));
    }
}
