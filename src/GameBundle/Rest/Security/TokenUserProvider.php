<?php
namespace GameBundle\Rest\Security;

use GameBundle\Entity\Player;
use GameBundle\Service\PlayerService;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class TokenUserProvider implements UserProviderInterface, UsernameByTokenProviderInterface
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
     * @param string $username
     * @return UserInterface
     */
    public function loadUserByUsername($username)
    {
        return $this->playerService->getByUsername($username);
    }

    /**
     * @param UserInterface $user
     * @return UserInterface
     * @throws UnsupportedUserException if the user is not supported
     */
    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException();
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass($class)
    {
        return Player::class === $class;
    }

    /**
     * @param string $token
     * @return string
     */
    public function getUsernameByToken(string $token): ?string
    {
        $player = $this->playerService->getByValidToken($token);

        if (!$player){
            return null;
        }

        return $player->getUsername();
    }
}