<?php
namespace GameBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class GameInstallCommand extends Command
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('game:install')
            ->setDescription('Install the game');
    }


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $create = $this->getApplication()->find('doctrine:database:create');

        $create->run(new ArrayInput([]), $output);

        $update = $this->getApplication()->find('doctrine:schema:update');

        $update->run(new ArrayInput(['--force' => true]), $output);

        $seed = $this->getApplication()->find('doctrine:fixtures:load');

        $seedInput = new ArrayInput([]);
        $seedInput->setInteractive(false);

        $seed->run($seedInput, $output);
    }
}