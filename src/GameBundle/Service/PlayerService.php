<?php
namespace GameBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use GameBundle\Entity\Player;
use GameBundle\Entity\Token;
use GameBundle\Exception\ValidationFailedException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * The service to manage players
 *
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class PlayerService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var int
     */
    private $tokenTimeout = 24; //hours

    /**
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param int $hours
     */
    public function setTokenTimeout(int $hours)
    {
        $this->tokenTimeout = $hours;
    }

    /**
     * @param Player $player
     */
    public function reset(Player $player)
    {
        $this->defaults($player);

        $this->entityManager->flush();
    }

    /**
     * @param string $username
     * @param string $password
     * @return Player
     */
    public function createOrRetrieve(string $username, string $password) : Player
    {
        /**
         * @var Player $player
         */
        $player = $this->entityManager->getRepository(Player::class)
            ->findOneBy(['username' => $username]);

        if (!$player){
            return $this->create($username, $password);
        }

        if (!$this->verifyCredentials($player, $password)){
            throw new ValidationFailedException('We found the player in our database, but you have provided wrong password');
        }

        return $player;
    }

    /**
     * @param string $secret
     * @return Player
     */
    public function getByValidToken(string $secret) : ?Player
    {
        /**
         * @var Token $token
         */
        $token = $this->entityManager->getRepository(Token::class)
            ->findOneBy(['secret' => $secret]);

        if (!$token){
            return null;
        }

        $expiresAt = $token->getCreatedAt();
        $expiresAt->modify('+'.$this->tokenTimeout.' hours');

        if ($expiresAt < new \DateTime()){
            return null;
        }

        return $token->getPlayer();
    }

    /**
     * @param string $username
     * @return Player|null
     */
    public function getByUsername(string $username) : ?Player
    {
        return $this->entityManager->getRepository(Player::class)
            ->findOneBy(['username' => $username]);
    }

    /**
     * @param string $username
     * @param string $password
     * @return null|Token
     */
    public function login(string $username, string $password) : ?Token
    {
        $player = $this->getByUsername($username);

        if (!$player){
            return null;
        }

        if (!$this->verifyCredentials($player, $password)){
            return null;
        }

        $token = $player->getToken();

        if (!$token){
            $token = new Token();

            $token->setPlayer($player);

            $this->entityManager->persist($token);
        }

        $token->setCreatedAt(new \DateTime());

        // The token is still not 100% secured, but for the purpose of this app it will work
        $token->setSecret(md5(uniqid(rand(), true)));

        $this->entityManager->flush();

        return $token;
    }

    /**
     * @param Player $player
     */
    public function logout(Player $player)
    {
        $token = $this->entityManager->getRepository(Token::class)
            ->findOneBy(['player' => $player]);

        if (!$token){
            return ;
        }

        $this->entityManager->remove($token);

        $this->entityManager->flush();
    }

    /**
     * @param Player $player
     * @param string $password
     * @return bool
     */
    private function verifyCredentials(Player $player, string $password) : bool
    {
        return $this->passwordEncoder->isPasswordValid($player, $password);
    }

    /**
     * @param string $username
     * @param string $password
     * @return Player
     */
    public function create(string $username, string $password) : Player
    {
        $player = $this->entityManager->getRepository(Player::class)
            ->findOneBy(['username' => $username]);

        if ($player) {
            throw new ValidationFailedException('The "'.$username.'" player already exists');
        }

        $player = new Player();

        $player->setUsername($username);

        $player->setPassword($this->passwordEncoder->encodePassword($player, $password));

        $this->defaults($player);

        $this->entityManager->persist($player);
        $this->entityManager->flush();

        return $player;
    }

    /**
     * @param Player $player
     */
    private function defaults(Player $player)
    {
        $player->setHealth(50);
        $player->setArtifacts([]);
        $player->setRoom(null);
    }
}