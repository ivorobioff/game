<?php
namespace GameBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="players")
 */
class Player implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type = "integer")
     * @ORM\GeneratedValue
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     *
     * @var string
     */
    private $username;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $password;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $health;

    /**
     * @ORM\ManyToOne(targetEntity="\GameBundle\Entity\Room")
     * @ORM\JoinColumn(name="room_id", referencedColumnName="id", onDelete="RESTRICT")
     *
     * @var Room
     */
    private $room;

    /**
     * @ORM\ManyToMany(targetEntity="\GameBundle\Entity\Artifact")
     * @ORM\JoinTable(name="players_artifacts",
     *      joinColumns={@ORM\JoinColumn(name="player_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="artifact_id", referencedColumnName="id", onDelete="CASCADE")})
     *
     * @var Artifact[]
     */
    private $artifacts;

    /**
     * @ORM\OneToOne(targetEntity="\GameBundle\Entity\Token", mappedBy="player")
     *
     * @var Token
     */
    private $token;

    public function __construct()
    {
        $this->artifacts = new ArrayCollection();
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    public function getUsername() : string
    {
        return $this->username;
    }

    /**
     * @param int $health
     */
    public function setHealth(int $health)
    {
        $this->health = $health;
    }

    /**
     * @param int $health
     */
    public function addHealth(int $health)
    {
        $this->health += $health;
    }

    /**
     * @return int
     */
    public function getHealth() : int
    {
        return $this->health;
    }

    /**
     * @param Room|null $room
     */
    public function setRoom(?Room $room)
    {
        $this->room = $room;
    }

    /**
     * @return Room|null
     */
    public function getRoom() : ?Room
    {
        return $this->room;
    }

    /**
     * @param Artifact[] $artifacts
     */
    public function setArtifacts($artifacts)
    {
        $this->artifacts->clear();

        foreach ($artifacts as $artifact){
            $this->artifacts->add($artifact);
        }
    }

    /**
     * @param Artifact $artifact
     */
    public function addArtifact(Artifact $artifact)
    {
        $this->artifacts->add($artifact);
    }

    /**
     * @return Artifact[]
     */
    public function getArtifacts()
    {
        return $this->artifacts;
    }

    /**
     * @param Artifact $artifact
     * @return bool
     */
    public function hasArtifact(Artifact $artifact) : bool
    {
        return $this->artifacts->contains($artifact);
    }

    /**
     * @param Artifact $artifact
     */

    public function removeArtifact(Artifact $artifact)
    {
        $this->artifacts->removeElement($artifact);
    }

    /**
     * @return Weapon[]
     */
    public function getWeapons()
    {
        return $this->artifacts->filter(function(Artifact $artifact){
            return $artifact instanceof Weapon;
        });
    }

    /**
     * @return int
     */
    public function getWeaponsPower() : int
    {
        $weaponsPower = 0;

        foreach ($this->getWeapons() as $weapon){
            $weaponsPower += $weapon->getPower();
        }

        return $weaponsPower;
    }

    /**
     * @return int
     */
    public function getOverallPower() : int
    {
        return $this->getHealth() + $this->getWeaponsPower();
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword() : string
    {
        return $this->password;
    }

    /**
     * @param Token $token
     */
    public function setToken(Token $token)
    {
        $this->token = $token;
    }

    /**
     * @return Token|null
     */
    public function getToken() : ?Token
    {
        return $this->token;
    }

    /**
     * @return array
     */
    public function getRoles() : array
    {
        return ['ROLE_PLAYER'];
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt() : ?string
    {
        return null;
    }

    /**
     *
     */
    public function eraseCredentials()
    {
        //
    }
}