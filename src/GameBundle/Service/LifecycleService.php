<?php
namespace GameBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use GameBundle\Scene\Scenario\InsideScenario;
use GameBundle\Scene\ScenarioInterface;
use GameBundle\Entity\Challenge;
use GameBundle\Entity\Guard;
use GameBundle\Entity\Health;
use GameBundle\Entity\History;
use GameBundle\Entity\Player;
use GameBundle\Entity\Room;
use GameBundle\Exception\InvalidLifecycleException;
use GameBundle\Objects\Inside;

/**
 * The service manages the overall workflow of the game
 *
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class LifecycleService
{
    /**
     * We need this to manage it's state when running the game on the command line.
     *
     * @var Inside
     */
    private $currentInside;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var PlayerService
     */
    private $playerService;

    /**
     * @param EntityManagerInterface $entityManager
     * @param PlayerService $playerService
     */
    public function __construct(EntityManagerInterface $entityManager, PlayerService $playerService)
    {
        $this->entityManager = $entityManager;
        $this->playerService = $playerService;
    }

    /**
     * Starts a game
     *
     * @param Player $player
     * @return ScenarioInterface
     */
    public function start(Player $player): ScenarioInterface
    {
        $room = $player->getRoom();

        if ($room === null) {

            /**
             * @var Room $room
             */
            $room = $this->entityManager->getRepository(Room::class)
                ->findOneBy(['initial' => true]);

            if (!$room){
                throw new \RuntimeException('There\'s no initial room in the game');
            }

            $player->setRoom($room);

            $this->entityManager->flush();
        }

        return new InsideScenario($this, $player, $this->enter($player, $room));
    }

    /**
     * Enters a room
     *
     * @param Player $player
     * @param Room $room
     * @return Inside
     */
    public function enter(Player $player, Room $room): Inside
    {
        /**
         * @var History $history
         */
        $history = $this->getHistory($room, $player);

        if ($history === null) {

            $history = new History();

            $history->setRoom($room);
            $history->setPlayer($player);

            $this->entityManager->persist($history);
            $this->entityManager->flush();
        }

        $player->setRoom($room);

        $this->entityManager->flush();

        $this->currentInside = new Inside();

        $this->currentInside->setRoom($room);

        if (!$history->getSolved()) {
            $this->currentInside->setChallenge($room->getChallenge());
        }

        if ($history->getGuarded()) {
            $this->currentInside->setGuard($room->getGuard());
        }

        if (!$history->getCollected()) {
            $this->currentInside->setArtifacts($room->getArtifacts());
        }

        $this->currentInside->setNextRooms($room->getNextRooms());

        return $this->currentInside;
    }

    /**
     *
     * Fights with a guard
     *
     * @param Player $player
     * @param Guard $guard
     * @return bool
     */
    public function fight(Player $player, Guard $guard): bool
    {
        $room = $player->getRoom();

        if (!$room) {
            throw new InvalidLifecycleException('The player is not in a room');
        }

        if ($guard->getRoom()->getId() != $room->getId()) {
            throw new InvalidLifecycleException(
                'The player cannot fight with this guard because they are in different rooms'
            );
        }

        /**
         * @var History $history
         */
        $history = $this->getHistory($room, $player);

        if ($room->getChallenge() && !$history->getSolved()) {
            throw new InvalidLifecycleException('The player cannot fight in this room until he solves the challenge');
        }

        $guardPower = $guard->getPower();

        if (($player->getOverallPower() - $guardPower) <= 0) {

            $this->reset($player);

            return false;
        }

        $fromHealth = $guardPower;

        foreach ($player->getWeapons() as $weapon){

            $fromHealth -= $weapon->getPower();

            $player->removeArtifact($weapon);

            if ($fromHealth <= 0){
                $fromHealth = 0;
                break ;
            }
        }

        $player->addHealth(-$fromHealth);

        $history->setGuarded(false);

        $this->entityManager->flush();

        $this->currentInside->setGuard(null);

        return true;
    }

    /**
     * Solves a challenge
     *
     * @param Player $player
     * @param Challenge $challenge
     * @return bool
     */
    public function solve(Player $player, Challenge $challenge): bool
    {
        $room = $player->getRoom();

        if (!$room) {
            throw new InvalidLifecycleException('The player is not in a room');
        }

        if ($challenge->getRoom()->getId() != $room->getId()) {
            throw new InvalidLifecycleException(
                'The player cannot solve this challenge because it is in a different room'
            );
        }

        $solution = $challenge->getSolution();

        if (!$player->hasArtifact($solution)){
            return false;
        }

        /**
         * @var History $history
         */
        $history = $this->getHistory($room, $player);

        $history->setSolved(true);

        $player->removeArtifact($solution);

        $this->entityManager->flush();

        $this->currentInside->setChallenge(null);

        return true;
    }

    /**
     * Goes back to the previous room
     *
     * @param Player $player
     * @return Inside
     */
    public function back(Player $player): Inside
    {
        $room = $player->getRoom();

        if (!$room) {
            throw new InvalidLifecycleException('The player has not entered any room yet');
        }

        $prevRoom = $room->getPreviousRoom();

        if ($prevRoom === null) {
            throw new InvalidLifecycleException('The player is in the starting room, thus, he cannot go back');
        }

        return $this->enter($player, $prevRoom);
    }

    /**
     * Collects all artifacts in the room
     *
     * @param Player $player
     * @param Room $room
     */
    public function collect(Player $player, Room $room)
    {
        /**
         * @var History $history
         */
        $history = $this->getHistory($room, $player);

        if (!$history) {
            throw new InvalidLifecycleException('The player must enter the room before collecting artifacts there');
        }

        if ($history->getCollected()) {
            return;
        }

        if ($room->getChallenge() && !$history->getSolved()) {
            throw new InvalidLifecycleException('The player cannot collect artifacts in this room until he solves the challenge');
        }

        if ($room->getGuard() && $history->getGuarded()) {
            throw new InvalidLifecycleException('The player cannot collect artifacts in this room until he kills the guard');
        }

        foreach ($room->getArtifacts() as $artifact) {

            if ($artifact instanceof Health) {
                $player->addHealth($artifact->getPercentage());
            } else {
                $player->addArtifact($artifact);
            }
        }

        $history->setCollected(true);

        $this->entityManager->flush();

        $this->currentInside->setArtifacts([]);
    }

    /**
     *
     * Resets the player to the point of the beginning and starts the game again
     *
     * @param Player $player
     * @return ScenarioInterface
     */
    public function replay(Player $player) : ScenarioInterface
    {
        $this->reset($player);

        return $this->start($player);
    }

    /**
     * @param Player $player
     */
    private function reset(Player $player)
    {
        $room = $player->getRoom();

        if (!$room) {
            // meaning the player is already reset

            return ;
        }

        $this->playerService->reset($player);

        $histories = $this->entityManager->getRepository(History::class)
            ->findBy(['player' => $player]);

        foreach ($histories as $history){
            $this->entityManager->remove($history);
        }

        $this->entityManager->flush();
    }

    /**
     * It's a simply shortcut
     *
     * @param Room $room
     * @param Player $player
     * @return null|History
     */
    private function getHistory(Room $room, Player $player) : ?History
    {
        return $this->entityManager->getRepository(History::class)
            ->findOneBy(['room' => $room, 'player' => $player]);
    }
}