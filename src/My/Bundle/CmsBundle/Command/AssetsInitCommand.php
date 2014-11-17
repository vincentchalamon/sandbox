<?php

/*
 * This file is part of the Sandbox package.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\CmsBundle\Command;

use Doctrine\Bundle\DoctrineBundle\Command\DoctrineCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * Init assets
 *
 * @author Vincent Chalamon <vincent@ylly.fr>
 */
class AssetsInitCommand extends DoctrineCommand
{

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('project:assets:init')
            ->setDescription('This command installs & dumps assets')
            ->addArgument('target', InputArgument::OPTIONAL, 'Target', 'web')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Install assets
        $this->runCommand('assets:install', $output, array('--symlink' => true, 'target' => $input->getArgument('target'), '--env' => $input->getOption('env')));
        $this->runCommand('assetic:dump', $output, array('write_to' => $input->getArgument('target'), '--env' => $input->getOption('env')));
    }

    /**
     * Run Symfony command
     *
     * @author Vincent Chalamon <vincent@ylly.fr>
     *
     * @param string          $name    Command name
     * @param OutputInterface $output  Output interface
     * @param array           $options Command options
     */
    protected function runCommand($name, OutputInterface $output, array $options = array())
    {
        $input = new ArrayInput(array_merge(array('command' => $name), $options));
        $input->setInteractive(false);
        $this->getApplication()->find($name)->run($input, $output);
    }
}
