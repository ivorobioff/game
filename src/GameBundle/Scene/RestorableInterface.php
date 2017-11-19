<?php
namespace GameBundle\Scene;

use GameBundle\Entity\Player;
use GameBundle\Service\LifecycleService;

/**
 * It tells that a scenario can be created (aka restored) from a provided player.
 * Scenarios implementing this method imply that they know how to create themselves based on the current player.
 *
 * This is useful when restoring scenarios between http requests
 *
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
interface RestorableInterface
{
    /**
     * this is a factory method
     *
     * @param LifecycleService $lifecycleService
     * @param Player $player
     * @return ScenarioInterface
     */
    public static function restore(LifecycleService $lifecycleService, Player $player) : ScenarioInterface;
}