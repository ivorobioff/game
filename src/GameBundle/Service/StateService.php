<?php
namespace GameBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use GameBundle\Entity\Player;
use GameBundle\Entity\State;
use GameBundle\Scene\ReferredScenarioAwareInterface;
use GameBundle\Scene\Scenario\ChallengeScenario;
use GameBundle\Scene\Scenario\CollectedScenario;
use GameBundle\Scene\Scenario\EndScenario;
use GameBundle\Scene\Scenario\GameOverScenario;
use GameBundle\Scene\Scenario\GuardScenario;
use GameBundle\Scene\Scenario\InsideScenario;
use GameBundle\Scene\Scenario\RoomScenario;
use GameBundle\Scene\Scenario\UnsolvedChallengeScenario;
use GameBundle\Scene\ScenarioInterface;

/**
 * The service helps to manage state of the game. It helps to remember and restore scenarios between http requests.
 * It's not used when playing the game on the command line.
 *
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class StateService
{
    /**
     * @var array
     */
    private $scenarioMapping = [
        ChallengeScenario::IDENTIFIER => ChallengeScenario::class,
        CollectedScenario::IDENTIFIER => CollectedScenario::class,
        EndScenario::IDENTIFIER => EndScenario::class,
        GameOverScenario::IDENTIFIER => GameOverScenario::class,
        GuardScenario::IDENTIFIER => GuardScenario::class,
        InsideScenario::IDENTIFIER => InsideScenario::class,
        RoomScenario::IDENTIFIER => RoomScenario::class,
        UnsolvedChallengeScenario::IDENTIFIER => UnsolvedChallengeScenario::class
    ];

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var LifecycleService
     */
    private $lifecycleService;

    /**
     * @param EntityManagerInterface $entityManager
     * @param LifecycleService $lifecycleService
     */
    public function __construct(EntityManagerInterface $entityManager, LifecycleService $lifecycleService) {
        $this->entityManager = $entityManager;
        $this->lifecycleService = $lifecycleService;
    }

    /**
     * Saves the current state of the game
     *
     * @param Player $player
     * @param ScenarioInterface $scenario
     */
    public function remember(
        Player $player,
        ?ScenarioInterface $scenario
    ) {
        $state = $this->entityManager->getRepository(State::class)
            ->findOneBy(['player' => $player]);

        if (!$state){
            $state = new State();
            $state->setPlayer($player);

            $this->entityManager->persist($state);
        }

        if ($scenario === null){
            $state->setScenario(null);
        } else {

            $identifier = $this->verifyIdentifier($scenario->getIdentifier());

            while($scenario instanceof ReferredScenarioAwareInterface){

                $scenario = $scenario->getReferredScenario();

                $identifier .= ':'.$this->verifyIdentifier($scenario->getIdentifier());
            }

            $state->setScenario($identifier);
        }

        $this->entityManager->flush();
    }

    /**
     *
     * Checks whether the provided identifier is supported by the service
     *
     * @param string $identifier
     * @return string
     */
    private function verifyIdentifier(string $identifier) : string
    {
        if (!isset($this->scenarioMapping[$identifier])){
            throw new \RuntimeException('The "'.$identifier.'" scenario cannot be remembered because it\'s not supported');
        }

        return $identifier;
    }

    /**
     * Restores the current scenario if any
     *
     * @param Player $player
     *
     * @return ScenarioInterface
     */
    public function restore(Player $player) : ?ScenarioInterface
    {
        /**
         * @var State $state
         */
        $state = $this->entityManager->getRepository(State::class)
            ->findOneBy(['player' => $player]);

        if (!$state){
            return null;
        }

        $scenarioIdentifier = $state->getScenario();

        if (!$scenarioIdentifier){
            return null;
        }

        if (strpos($scenarioIdentifier, ':') === false){
            return $this->restoring($scenarioIdentifier, $player);
        } else {
            $scenarioIdentifiers = explode(':', $scenarioIdentifier);

            $scenarioIdentifier = array_shift($scenarioIdentifiers);

            $primaryScenario = $this->restoring($scenarioIdentifier, $player);

            $scenario = $primaryScenario;

            foreach ($scenarioIdentifiers as $scenarioIdentifier) {

                if (!$scenario instanceof ReferredScenarioAwareInterface){
                    throw new \RuntimeException('The "'.get_class($scenario).'" is expected to implement "'.ReferredScenarioAwareInterface::class.'", but it does not.');
                }

                $restoredScenario = $this->restoring($scenarioIdentifier, $player);

                $scenario->setReferredScenario($restoredScenario);

                $scenario = $restoredScenario;
            }

            return $primaryScenario;
        }
    }

    /**
     * A shortcut method to store a specific scenario by an identifier
     *
     * @param string $scenarioIdentifier
     * @param Player $player
     * @return ScenarioInterface
     */
    private function restoring(string $scenarioIdentifier, Player $player) : ScenarioInterface
    {
        if (!isset($this->scenarioMapping[$scenarioIdentifier])){
            throw new \RuntimeException('Unable to locate class for the "'.$scenarioIdentifier.'" interface scenario');
        }

        $scenarioClass = $this->scenarioMapping[$scenarioIdentifier];

        return $scenarioClass::restore($this->lifecycleService, $player);
    }
}