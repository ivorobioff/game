<?php
namespace GameBundle\Rest\Resource;

use GameBundle\Entity\Player;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class PlayerResource
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var int
     */
    private $health;

    /**
     * @var ArtifactResource[]
     */
    private $artifacts = [];

    /**
     * @param Player $player
     */
    public function __construct(Player $player)
    {
        $this->username = $player->getUsername();

        $this->health = $player->getHealth();

        $artifactResourceFactory = new ArtifactResourceFactory();

        foreach ($player->getArtifacts() as $artifact) {
            $this->artifacts[] = $artifactResourceFactory->create($artifact);
        }
    }
}