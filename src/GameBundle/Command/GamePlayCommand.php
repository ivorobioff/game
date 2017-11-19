<?php

namespace GameBundle\Command;

use GameBundle\Service\LifecycleService;
use GameBundle\Service\PlayerService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class GamePlayCommand extends Command implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('game:play')
            ->setDescription('Play the game')
            ->addArgument('username', InputArgument::REQUIRED, 'Username to login the game')
            ->addArgument('password', InputArgument::REQUIRED, 'Password to login the game');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * @var QuestionHelper $helper
         */
        $helper = $this->getHelper('question');

        /**
         * @var LifecycleService $lifecycleService
         */
        $lifecycleService = $this->container->get('game.service.lifecycle');

        /**
         * @var PlayerService $playerService
         */
        $playerService = $this->container->get('game.service.player');

        $username = $input->getArgument('username');
        $password = $input->getArgument('password');

        $player = $playerService->createOrRetrieve($username, $password);

        $scenario = $lifecycleService->start($player);

        while($scenario !== null ){

            $identifiers = [];

            foreach ($scenario->getChoices() as $choice){
                $identifiers[$choice->getTitle()] = $choice->getIdentifier();
            }

            $question = new ChoiceQuestion($scenario->getDescription(), array_keys($identifiers));

            $result = $helper->ask($input, $output, $question);

            $scenario = $scenario->choose($identifiers[$result]);
        }
    }
}
