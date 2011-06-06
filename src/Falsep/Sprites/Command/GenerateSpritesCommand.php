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

use Symfony\Component\Console\Command\Command,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Output\OutputInterface;

use Falsep\Sprites\Generator;

use Imagine;

class GenerateSpritesCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('sprites:generate')
            ->setDefinition(array(
                new InputArgument('sourceDirectory', InputArgument::REQUIRED),
                new InputArgument('fileNamePattern', InputArgument::REQUIRED),
                new InputArgument('targetImage', InputArgument::REQUIRED),
                new InputArgument('targetStylesheet', InputArgument::REQUIRED),
                new InputArgument('cssSelector', InputArgument::REQUIRED),
                new InputOption('imagine', null, InputOption::VALUE_OPTIONAL),
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
        $output->writeln(sprintf(
            'Generating sprites for <comment>%s/%s</comment>',
            $fileNamePattern = $input->getArgument('fileNamePattern'),
            $sourceDirectory = $input->getArgument('sourceDirectory')
        ));
        $output->writeln(sprintf(
            '  > target image: <info>%s</info>',
            $targetImage = $input->getArgument('targetImage')
        ));
        $output->writeln(sprintf(
            '  > target stylesheet: <info>%s</info>',
            $targetStylesheet = $input->getArgument('targetStylesheet')
        ));
        $output->writeln(sprintf(
            '  > css selector: <info>%s</info>',
            $cssSelector = $input->getArgument('cssSelector')
        ));

        $generator = new Generator(Generator::getImagineDriver($input->getOption('imagine')));
        $generator->getFinder()->name($fileNamePattern)->in($sourceDirectory);
        $generator->generate($targetImage, $targetStylesheet, $cssSelector);
    }
}
