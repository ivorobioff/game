<?php
namespace GameBundle\Objects;

use GameBundle\Entity\Artifact;
use GameBundle\Entity\Challenge;
use GameBundle\Entity\Guard;
use GameBundle\Entity\Room;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class Inside
{
    /**
     * @var Room
     */
    private $room;

    /**
     * @var Guard
     */
    private $guard;

    /**
     * @var Artifact[]
     */
    private $artifacts = [];

    /**
     * @var Challenge
     */
    private $challenge;

    /**
     * @var Room[]
     */
    private $nextRooms = [];

    /**
     * @param Room $room
     */
    public function setRoom(Room $room)
    {
        $this->room = $room;
    }

    /**
     * @return Room
     */
    public function getRoom() : Room
    {
        return $this->room;
    }


    /**
     * @param null|Guard $guard
     */
    public function setGuard(?Guard $guard)
    {
        $this->guard = $guard;
    }

    /**
     * @return Guard
     */
    public function getGuard() : ?Guard
    {
        return $this->guard;
    }

    /**
     * @param Artifact[] $artifacts
     */
    public function setArtifacts($artifacts)
    {
        $this->artifacts = $artifacts;
    }

    /**
     * @return Artifact[]
     */
    public function getArtifacts()
    {
        return $this->artifacts;
    }

    /**
     * @param null|Challenge $challenge
     */
    public function setChallenge(?Challenge $challenge)
    {
        $this->challenge = $challenge;
    }

    /**
     * @return null|Challenge
     */
    public function getChallenge() : ?Challenge
    {
        return $this->challenge;
    }

    /**
     * @param Room[] $nextRooms
     */
    public function setNextRooms($nextRooms)
    {
        $this->nextRooms = $nextRooms;
    }

    /**
     * @return Room[]
     */
    public function getNextRooms()
    {
        return $this->nextRooms;
    }
}