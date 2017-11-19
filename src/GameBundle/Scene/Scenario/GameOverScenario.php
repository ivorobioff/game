<?php
namespace GameBundle\Scene\Scenario;

use GameBundle\Entity\Player;
use GameBundle\Objects\Inside;
use GameBundle\Scene\Choice\BackChoice;
use GameBundle\Scene\ScenarioInterface;
use GameBundle\Service\LifecycleService;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class GameOverScenario extends AbstractScenario
{
    const IDENTIFIER = 'game-over';

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Game over!';
    }

    public function getChoices(): array
    {
        $choices = parent::getChoices();

        unset($choices[BackChoice::IDENTIFIER]);

        return $choices;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return self::IDENTIFIER;
    }

    /**
     * @param LifecycleService $lifecycleService
     * @param Player $player
     * @return ScenarioInterface
     */
    public static function restore(LifecycleService $lifecycleService, Player $player): ScenarioInterface
    {
        return new static($lifecycleService, $player);
    }

    protected static function restoring(
        LifecycleService $lifecycleService,
        Player $player,
        Inside $inside
    ): ScenarioInterface {
        throw new \RuntimeException('This scenario needs to be restored in different way');
    }
}