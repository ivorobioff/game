<?php
namespace GameBundle\Controller;

use GameBundle\Entity\Player;
use GameBundle\Exception\ConstraintViolationException;
use GameBundle\Rest\Payload\CredentialsPayload;
use GameBundle\Rest\Resource\PlayerResource;
use GameBundle\Rest\Resource\TokenResource;
use GameBundle\Rest\Resource\ValidationErrorsResource;
use GameBundle\Service\PlayerService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class PlayerController extends Controller
{
    /**
     * @var PlayerService
     */
    private $playerService;

    /**
     * @param PlayerService $playerService
     */
    public function __construct(PlayerService $playerService)
    {
        $this->playerService = $playerService;
    }

    /**
     * @Route("/api/players/current", name="api_view_player")
     * @Method("GET")
     *
     * @param Player $player
     * @return PlayerResource
     */
    public function viewAction(Player $player) : PlayerResource
    {
        return new PlayerResource($player);
    }

    /**
     * @Route("/api/players", name="api_store_player")
     * @Method("POST")
     *
     * @ParamConverter("payload", converter="fos_rest.request_body")
     *
     * @param CredentialsPayload $payload
     * @param ConstraintViolationListInterface $validationErrors
     *
     * @return PlayerResource|ValidationErrorsResource
     */
    public function storeAction(CredentialsPayload $payload, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0){
            throw new ConstraintViolationException($validationErrors);
        }

        $player = $this->playerService->create($payload->getUsername(), $payload->getPassword());

        return new PlayerResource($player);
    }

    /**
     * @Route("/api/players/current/login", name="api_login_player")
     * @Method("POST")
     *
     * @ParamConverter("payload", converter="fos_rest.request_body")
     *
     * @param CredentialsPayload $payload
     * @return TokenResource
     */
    public function loginAction(CredentialsPayload $payload) : TokenResource
    {
        $token = $this->playerService
            ->login($payload->getUsername(), $payload->getPassword());

        if (!$token){
            throw new AccessDeniedHttpException('The provided credentials are not valid');
        }

        return new TokenResource($token);
    }

    /**
     * @Route("/api/players/current/logout", name="api_logout_player")
     * @Method("POST")
     *
     * @param Player $player
     */
    public function logoutAction(Player $player)
    {
        $this->playerService->logout($player);
    }

}