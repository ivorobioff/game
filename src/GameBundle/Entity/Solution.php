<?php
namespace GameBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 *
 * @ORM\Entity
 */
class Solution extends Artifact
{
    /**
     * @ORM\OneToOne(targetEntity="\GameBundle\Entity\Challenge", inversedBy="solution")
     * @ORM\JoinColumn(name="challenge_id", referencedColumnName="id", onDelete="CASCADE")
     *
     * @var Challenge
     */
    private $challenge;

    /**
     * @param Challenge $challenge
     */
    public function setChallenge(Challenge $challenge)
    {
        $this->challenge = $challenge;
    }

    /**
     * @return Challenge
     */
    public function getChallenge() : Challenge
    {
        return $this->challenge;
    }
}