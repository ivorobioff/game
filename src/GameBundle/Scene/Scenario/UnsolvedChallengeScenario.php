<?php
namespace GameBundle\Scene\Scenario;

use GameBundle\Entity\Player;
use GameBundle\Objects\Inside;
use GameBundle\Scene\ScenarioInterface;
use GameBundle\Service\LifecycleService;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class UnsolvedChallengeScenario extends AbstractScenario
{
    const IDENTIFIER = 'unsolved-challenge';

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'You missed something. The solution is somewhere around.';
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return self::IDENTIFIER;
    }

    protected static function restoring(
        LifecycleService $lifecycleService,
        Player $player,
        Inside $inside
    ): ScenarioInterface {
        return new static($lifecycleService, $player);
    }
}