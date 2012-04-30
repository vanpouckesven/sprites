<?php

/*
 * This file is part of the Sprites package.
 *
 * (c) Pierre Minnieur <pm@pierre-minnieur.de>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Sprites\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Sprites\Processor\DynamicProcessor;

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
                new InputArgument('source', InputArgument::REQUIRED, 'The path to the source directory.'),
                new InputArgument('pattern', InputArgument::REQUIRED, 'The pattern to find files.'),
                new InputArgument('image', InputArgument::REQUIRED, 'The path to the target image.'),
                new InputArgument('stylesheet', InputArgument::REQUIRED, 'The path to the target stylesheet.'),
                new InputArgument('selector', InputArgument::REQUIRED, 'The CSS selector.'),
                new InputOption('driver', 'd', InputOption::VALUE_OPTIONAL, 'The Imagine driver.', 'gd'),
                new InputOption('options', 'o', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The Imagine driver options.', array()),
                new InputOption('color', 'c', InputOption::VALUE_OPTIONAL, 'The RGB color model.', 'fff'),
                new InputOption('alpha', 'a', InputOption::VALUE_OPTIONAL, 'The alpha compositing.', 100),
            ))
            ->setDescription('Generate an image sprite and CSS stylesheet with dynamic dimensions.')
            ->setHelp(<<<EOT
The <info>generate:dynamic</info> command generates image sprites and CSS
stylesheets with dynamic dimensions:

  <info>./sprites generate:dynamic --driver=gd web/images/flags "*.png" web/images/flags.png web/css/flags.css ".flag.%s"</info>
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
