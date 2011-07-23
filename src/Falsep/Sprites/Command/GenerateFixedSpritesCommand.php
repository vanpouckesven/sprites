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

use Falsep\Sprites\Processor\FixedProcessor;

class GenerateFixedSpritesCommand extends GenerateSpritesCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('generate:fixed')
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
                new InputOption('resize', 'r', InputOption::VALUE_OPTIONAL, 'Whether to resize the image if it exceeds the fixed width.', false),
                new InputOption('width', 'w', InputOption::VALUE_REQUIRED, 'The width of an single image.'),
            ))
            ->setDescription('Generate an image sprite and CSS stylesheet with a fixed width dimension.')
            ->setHelp(<<<EOT
The <info>generate:fixed</info> command generates image sprites and CSS
stylesheets with a fixed width dimension:

  <info>./sprites generate:fixed --driver=gd --width=16 --resize=1 web/images/icons "*.png" web/images/icons.png web/css/icons.css ".icon.%s"</info>
EOT
            )
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configuration = $this->getConfiguration($input);
        $configuration->setWidth($input->getOption('width'));

        $resize = (boolean) $input->getOption('resize') ? true : false;
        $processor = new FixedProcessor(array('resize' => $resize));
        $processor->process($configuration);
    }
}
