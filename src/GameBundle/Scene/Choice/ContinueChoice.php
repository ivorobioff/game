<?php
namespace GameBundle\Scene\Choice;

use GameBundle\Entity\Player;
use GameBundle\Scene\ScenarioInterface;
use GameBundle\Service\LifecycleService;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class ContinueChoice extends AbstractChoice
{
    const IDENTIFIER = 'continue';

    /**
     * @var ScenarioInterface
     */
    private $scenario;

    /**
     * @param LifecycleService $lifecycleService
     * @param Player $player
     * @param ScenarioInterface $scenario
     */
    public function __construct(
        LifecycleService $lifecycleService,
        Player $player,
        ScenarioInterface $scenario
    ) {
        parent::__construct($lifecycleService, $player);
        $this->scenario = $scenario;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return 'Continue';
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return self::IDENTIFIER;
    }

    /**
     * @return null|ScenarioInterface
     */
    public function forward(): ?ScenarioInterface
    {
        return $this->scenario;
    }
}