<?php
namespace GameBundle\Scene\Scenario;

use GameBundle\Exception\InvalidLifecycleException;
use GameBundle\Objects\Inside;
use GameBundle\Scene\Choice\BackChoice;
use GameBundle\Scene\Choice\QuitChoice;
use GameBundle\Scene\Choice\ReplayChoice;
use GameBundle\Scene\ChoiceInterface;
use GameBundle\Scene\RestorableInterface;
use GameBundle\Scene\ScenarioInterface;
use GameBundle\Entity\Player;
use GameBundle\Service\LifecycleService;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
abstract class AbstractScenario implements ScenarioInterface, RestorableInterface
{
    /**
     * @var LifecycleService
     */
    protected $lifecycleService;

    /**
     * @var Player
     */
    protected $player;

    /**
     * @param LifecycleService $lifecycleService
     * @param Player $player
     */
    public function __construct(LifecycleService $lifecycleService, Player $player)
    {
        $this->lifecycleService = $lifecycleService;
        $this->player = $player;
    }

    /**
     * @return array|ChoiceInterface[]
     */
    public function getChoices(): array
    {
        $quitChoice = new QuitChoice();
        $backChoice = new BackChoice($this->lifecycleService, $this->player);
        $replayChoice = new ReplayChoice($this->lifecycleService, $this->player);

        return [
            $quitChoice->getIdentifier() => $quitChoice,
            $replayChoice->getIdentifier() => $replayChoice,
            $backChoice->getIdentifier() => $backChoice,
        ];
    }

    /**
     * @param string $identifier
     * @return null|ScenarioInterface
     */
    public function choose(string $identifier): ?ScenarioInterface
    {
        $choice = $this->getChoices()[$identifier] ?? null;

        if (!$choice){
            throw new InvalidLifecycleException('The player has chosen nonexistent choice');
        }

        return $choice->forward();
    }

    /**
     * @param LifecycleService $lifecycleService
     * @param Player $player
     * @return ScenarioInterface
     */
    public static function restore(LifecycleService $lifecycleService, Player $player): ScenarioInterface
    {
        $inside = $lifecycleService->enter($player, $player->getRoom());

        return static::restoring($lifecycleService, $player, $inside);
    }

    abstract protected static function restoring(
        LifecycleService $lifecycleService, Player $player, Inside $inside) : ScenarioInterface;
}