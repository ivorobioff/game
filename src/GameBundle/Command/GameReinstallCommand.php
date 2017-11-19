<?php
namespace GameBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class GameReinstallCommand extends Command
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('game:reinstall')
            ->setDescription('Reinstall the game');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $create = $this->getApplication()->find('doctrine:database:drop');

        $create->run(new ArrayInput(['--force' => true]), $output);

        $install = $this->getApplication()->find('game:install');

        $install->run(new ArrayInput([]), $output);
    }
}