<?php
namespace GameBundle\Controller;

use GameBundle\Entity\Player;
use GameBundle\Rest\Payload\ChoosePayload;
use GameBundle\Rest\Resource\ScenarioResource;
use GameBundle\Service\LifecycleService;
use GameBundle\Service\PlayerService;
use GameBundle\Service\StateService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class GameController extends Controller
{
    /**
     * @var LifecycleService
     */
    private $lifecycleService;

    /**
     * @var PlayerService
     */
    private $stateService;

    /**
     * @param LifecycleService $lifecycleService
     * @param StateService $stateService
     */
    public function __construct(LifecycleService $lifecycleService, StateService $stateService)
    {
        $this->lifecycleService = $lifecycleService;
        $this->stateService = $stateService;
    }

    /**
     * @param Player $player
     *
     * @Route("/api/games", name="api_game_start")
     * @Method("POST")
     *
     * @return ScenarioResource
     */
    public function startAction(Player $player) : ScenarioResource
    {
        $scenario = $this->lifecycleService->start($player);

        $this->stateService->remember($player, $scenario);

        return new ScenarioResource($scenario);
    }


    /**
     * @param Player $player
     *
     * @Route("/api/games/current/scenarios/current", name="api_game_scenario")
     * @Method("GET")
     *
     * @return ScenarioResource
     */
    public function scenarioAction(Player $player) : ?ScenarioResource
    {
        $scenario = $this->stateService->restore($player);

        if (!$scenario){
            return null;
        }

        return new ScenarioResource($scenario);
    }

    /**
     * @param Player $player
     * @param ChoosePayload $payload
     *
     * @Route("/api/games/current/scenarios/current/choices", name="api_game_choose")
     * @Method("POST")
     *
     * @return ScenarioResource|null
     */
    public function chooseAction(Player $player, ChoosePayload $payload) : ?ScenarioResource
    {
        $scenario = $this->stateService->restore($player);

        if (!$scenario){
            throw new BadRequestHttpException('The choice cannot be made because you don\'t have any scenario');
        }

        $scenario = $scenario->choose($payload->getIdentifier());

        $this->stateService->remember($player, $scenario);

        if (!$scenario) {
            return null;
        }

        return new ScenarioResource($scenario);
    }
}