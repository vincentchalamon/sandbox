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
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * Reset project
 *
 * @author Vincent Chalamon <vincent@ylly.fr>
 */
class ResetCommand extends DoctrineCommand
{

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('project:reset')->setDescription('This command reset project');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Reset database
        $this->runCommand('doctrine:schema:update', $output, array('--force' => true, '--env' => $input->getOption('env')));
        $this->runCommand('doctrine:fixtures:load', $output, array('-n' => true, '--env' => $input->getOption('env')));

        // Install assets
        $this->runCommand('project:assets:init', $output, array('--env' => 'dev'));
        $this->runCommand('project:assets:init', $output, array('--env' => 'prod', '--no-debug' => true));
        $this->runCommand('project:assets:init', $output, array('--env' => 'admin', '--no-debug' => true, 'target' => 'admin'));
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
