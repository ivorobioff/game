<?php
namespace GameBundle\Scene\Choice;

use GameBundle\Scene\Scenario\CollectedScenario;
use GameBundle\Scene\Scenario\InsideScenario;
use GameBundle\Scene\ScenarioInterface;
use GameBundle\Entity\Player;
use GameBundle\Objects\Inside;
use GameBundle\Service\LifecycleService;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class CollectChoice extends AbstractChoice
{
    const IDENTIFIER = 'collect';

    /**
     * @var Inside
     */
    private $inside;

    /**
     * @param LifecycleService $lifecycleService
     * @param Player $player
     * @param Inside $inside
     */
    public function __construct(LifecycleService $lifecycleService, Player $player, Inside $inside)
    {
        parent::__construct($lifecycleService, $player);
        $this->inside = $inside;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return 'Collect artifacts';
    }

    /**
     * @return null|ScenarioInterface
     */
    public function forward(): ?ScenarioInterface
    {
        $artifacts = $this->inside->getArtifacts();

        $this->lifecycleService->collect($this->player, $this->inside->getRoom());

        return new CollectedScenario(
            $this->lifecycleService,
            $this->player,
            $artifacts,
            new InsideScenario($this->lifecycleService, $this->player, $this->inside)
        );
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return self::IDENTIFIER;
    }
}