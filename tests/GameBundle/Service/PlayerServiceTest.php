<?php
namespace Tests\GameBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use GameBundle\Entity\Hint;
use GameBundle\Entity\Player;
use GameBundle\Entity\Room;
use GameBundle\Entity\Token;
use GameBundle\Entity\Weapon;
use GameBundle\Service\PlayerService;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class PlayerServiceTest extends TestCase
{
    public function testReset()
    {
        /**
         * @var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject $entityManager
         */
        $entityManager = $this
            ->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();


        /**
         * @var UserPasswordEncoderInterface $encoder
         */
        $encoder = $this
                ->getMockBuilder(UserPasswordEncoderInterface::class)
                ->disableOriginalConstructor()
                ->getMock();

        $playerService = new PlayerService($entityManager, $encoder);

        $player = new Player();

        $player->setHealth(102);
        $player->addArtifact(new Weapon());
        $player->addArtifact(new Hint());
        $player->setRoom(new Room());

        $playerService->reset($player);

        Assert::assertEquals(50, $player->getHealth());
        Assert::assertNull($player->getRoom());
        Assert::assertCount(0, $player->getArtifacts());
    }

    public function testGetByValidToken()
    {
        /**
         * @var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject $entityManager
         */
        $entityManager = $this
            ->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $tokenRepository = $this
                ->getMockBuilder(EntityRepository::class)
                ->disableOriginalConstructor()
                ->getMock();

        $token = new Token();
        $token->setCreatedAt(new \DateTime('-48 hours'));
        $token->setPlayer(new Player());

        $tokenRepository->method('findOneBy')->willReturn($token);

        $entityManager
            ->method('getRepository')
            ->with(Token::class)
            ->willReturn($tokenRepository);

        /**
         * @var UserPasswordEncoderInterface $encoder
         */
        $encoder = $this
            ->getMockBuilder(UserPasswordEncoderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $playerService = new PlayerService($entityManager, $encoder);

        $player = $playerService->getByValidToken('test');

        Assert::assertNull($player);

        $token->setCreatedAt(new \DateTime('-10 hours'));

        $player = $playerService->getByValidToken('test');

        Assert::assertNotNull($player);
    }

}