<?php
namespace GameBundle\Scene\Scenario;

use GameBundle\Entity\Player;
use GameBundle\Objects\Inside;
use GameBundle\Scene\ScenarioInterface;
use GameBundle\Service\LifecycleService;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class EndScenario extends AbstractScenario
{
    const IDENTIFIER = 'end';

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Congratulations! You have reached the final room. Here\'s what you can do next.';
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