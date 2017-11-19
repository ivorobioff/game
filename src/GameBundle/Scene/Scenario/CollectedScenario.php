<?php
namespace GameBundle\Scene\Scenario;

use GameBundle\Entity\Artifact;
use GameBundle\Entity\Health;
use GameBundle\Entity\Hint;
use GameBundle\Entity\Player;
use GameBundle\Entity\Solution;
use GameBundle\Entity\Weapon;
use GameBundle\Objects\Inside;
use GameBundle\Scene\Choice\BackChoice;
use GameBundle\Scene\Choice\ContinueChoice;
use GameBundle\Scene\ChoiceInterface;
use GameBundle\Scene\ReferredScenarioAwareInterface;
use GameBundle\Scene\ScenarioInterface;
use GameBundle\Service\LifecycleService;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class CollectedScenario extends AbstractScenario implements ReferredScenarioAwareInterface
{
    const IDENTIFIER = 'collected';

    /**
     * @var Artifact[]
     */
    private $artifacts;

    /**
     * @var ScenarioInterface
     */
    private $nextScenario;

    /**
     * @param LifecycleService $lifecycleService
     * @param Player $player
     * @param Artifact[] $artifacts
     * @param ScenarioInterface $nextScenario
     */
    public function __construct(
        LifecycleService $lifecycleService,
        Player $player,
        $artifacts,
        ScenarioInterface $nextScenario = null
    ) {
        parent::__construct($lifecycleService, $player);

        $this->artifacts = $artifacts;
        $this->nextScenario = $nextScenario;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        $description = '';
        $delimiter = '';

        foreach ($this->artifacts as $artifact){
            if ($artifact instanceof Health){
                $prefix = 'Health [+'.$artifact->getPercentage().']:';
            } elseif ($artifact instanceof Weapon){
                $prefix = 'Weapon ['.$artifact->getPower().']:';
            } elseif ($artifact instanceof Solution){
                $prefix = 'Solution:';
            } elseif ($artifact instanceof Hint){
                $prefix = 'Hint:';
            } else {
                throw new \RuntimeException('Unknown artifact');
            }

            $description .= $delimiter.$prefix. ' '.$artifact->getDescription();

            $delimiter = ' ';
        }

        return $description;
    }

    /**
     * @return ChoiceInterface[]
     */
    public function getChoices(): array
    {
        $choices = parent::getChoices();

        unset($choices[BackChoice::IDENTIFIER]);

        $continueChoice = new ContinueChoice($this->lifecycleService, $this->player, $this->nextScenario);

        $choices[$continueChoice->getIdentifier()] = $continueChoice;

        return $choices;
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
        return new static($lifecycleService, $player, $inside->getArtifacts());
    }

    /**
     * @return ScenarioInterface
     */
    public function getReferredScenario(): ScenarioInterface
    {
        return $this->nextScenario;
    }

    /**
     * @param ScenarioInterface $scenario
     */
    public function setReferredScenario(ScenarioInterface $scenario)
    {
        $this->nextScenario = $scenario;
    }
}