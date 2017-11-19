<?php
namespace GameBundle\Scene\Choice;

use GameBundle\Scene\ChoiceInterface;
use GameBundle\Entity\Player;
use GameBundle\Service\LifecycleService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
abstract class AbstractChoice implements ChoiceInterface
{
    /**
     * @var ContainerInterface
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
}