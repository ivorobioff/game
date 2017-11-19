<?php
namespace Tests\GameBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use GameBundle\Entity\Challenge;
use GameBundle\Entity\Guard;
use GameBundle\Entity\History;
use GameBundle\Entity\Player;
use GameBundle\Entity\Room;
use GameBundle\Entity\Weapon;
use GameBundle\Exception\InvalidLifecycleException;
use GameBundle\Service\LifecycleService;
use GameBundle\Service\PlayerService;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class LifecycleServiceTest extends TestCase
{
    public function testStartWhenNoInitialRoom()
    {
        /**
         * @var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject $entityManager
         */
        $entityManager = $this
            ->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $repository = $this
            ->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $entityManager->method('getRepository')
            ->with(Room::class)
            ->willReturn($repository);

        /**
         * @var PlayerService $playerService
         */
        $playerService = $this->getMockBuilder(PlayerService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $lifecycleService = new LifecycleService($entityManager, $playerService);

        $this->expectExceptionMessage('There\'s no initial room in the game');

        $lifecycleService->start(new Player());
    }

    public function testFightWhenPlayerNotInRoom()
    {
        /**
         * @var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject $entityManager
         */
        $entityManager = $this
            ->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        /**
         * @var PlayerService $playerService
         */
        $playerService = $this->getMockBuilder(PlayerService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $lifecycleService = new LifecycleService($entityManager, $playerService);

        $this->expectException(InvalidLifecycleException::class);
        $this->expectExceptionMessage('The player is not in a room');

        $lifecycleService->fight(new Player(), new Guard());
    }

    public function testFightWhenPlayerAndGuardInDifferentRooms()
    {
        /**
         * @var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject $entityManager
         */
        $entityManager = $this
            ->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        /**
         * @var PlayerService $playerService
         */
        $playerService = $this->getMockBuilder(PlayerService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $lifecycleService = new LifecycleService($entityManager, $playerService);


        $this->expectException(InvalidLifecycleException::class);
        $this->expectExceptionMessage('The player cannot fight with this guard because they are in different rooms');

        $room1 = new Room();
        $room1->setId(1);

        $player = new Player();
        $player->setRoom($room1);

        $room2 = new Room();
        $room2->setId(2);

        $guard = new Guard();
        $guard->setRoom($room2);

        $lifecycleService->fight($player, $guard);
    }

    public function testFightWhenUnresolvedChallengeInRoom()
    {
        /**
         * @var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject $entityManager
         */
        $entityManager = $this
            ->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $historyRepository = $this
            ->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $history = new History();

        $historyRepository->method('findOneBy')->willReturn($history);

        $entityManager
            ->method('getRepository')
            ->with(History::class)
            ->willReturn($historyRepository);

        /**
         * @var PlayerService $playerService
         */
        $playerService = $this->getMockBuilder(PlayerService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $lifecycleService = new LifecycleService($entityManager, $playerService);

        $this->expectException(InvalidLifecycleException::class);
        $this->expectExceptionMessage('The player cannot fight in this room until he solves the challenge');

        $room = new Room();

        $room->setId(1);
        
        $player = new Player();
        $player->setRoom($room);

        $guard = new Guard();
        $guard->setRoom($room);

        $room->setChallenge(new Challenge());

        $lifecycleService->fight($player, $guard);
    }

    public function testHealthCalculationAfterFight()
    {
        /**
         * @var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject $entityManager
         */
        $entityManager = $this
            ->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $historyRepository = $this
            ->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $historyRepository->method('findOneBy')->willReturn(new History());

        $entityManager
            ->method('getRepository')
            ->with(History::class)
            ->willReturn($historyRepository);

        /**
         * @var PlayerService $playerService
         */
        $playerService = $this->getMockBuilder(PlayerService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $lifecycleService = new LifecycleService($entityManager, $playerService);

        $room = new Room();

        $room->setId(1);

        $player = new Player();
        $player->setRoom($room);

        $guard = new Guard();
        $guard->setRoom($room);

        $guard->setPower(60);
        $player->setHealth(100);

        $weapon1 = new Weapon();
        $weapon1->setPower(70);

        $player->addArtifact($weapon1);

        $weapon2 = new Weapon();
        $weapon2->setPower(50);

        $player->addArtifact($weapon2);

        $lifecycleService->enter($player, $player->getRoom());
        $lifecycleService->fight($player, $guard);

        Assert::assertEquals(100, $player->getHealth());
        Assert::assertCount(1, $player->getArtifacts());

        $lifecycleService->fight($player, $guard);

        Assert::assertEquals(90, $player->getHealth());
        Assert::assertCount(0, $player->getArtifacts());
    }

    public function testSolveWhenPlayerNotInRoom()
    {
        /**
         * @var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject $entityManager
         */
        $entityManager = $this
            ->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        /**
         * @var PlayerService $playerService
         */
        $playerService = $this->getMockBuilder(PlayerService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $lifecycleService = new LifecycleService($entityManager, $playerService);

        $this->expectException(InvalidLifecycleException::class);
        $this->expectExceptionMessage('The player is not in a room');

        $lifecycleService->solve(new Player(), new Challenge());
    }

    public function testSolveWhenPlayerAndChallengeInDifferentRooms()
    {
        /**
         * @var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject $entityManager
         */
        $entityManager = $this
            ->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        /**
         * @var PlayerService $playerService
         */
        $playerService = $this->getMockBuilder(PlayerService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $lifecycleService = new LifecycleService($entityManager, $playerService);


        $this->expectException(InvalidLifecycleException::class);
        $this->expectExceptionMessage('The player cannot solve this challenge because it is in a different room');

        $room1 = new Room();
        $room1->setId(1);

        $player = new Player();
        $player->setRoom($room1);

        $room2 = new Room();
        $room2->setId(2);

        $challenge = new Challenge();
        $challenge->setRoom($room2);

        $lifecycleService->solve($player, $challenge);
    }

    public function testBackWhenPlayerNotInRoom()
    {
        /**
         * @var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject $entityManager
         */
        $entityManager = $this
            ->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        /**
         * @var PlayerService $playerService
         */
        $playerService = $this->getMockBuilder(PlayerService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $lifecycleService = new LifecycleService($entityManager, $playerService);

        $this->expectException(InvalidLifecycleException::class);
        $this->expectExceptionMessage('The player has not entered any room yet');

        $lifecycleService->back(new Player());
    }

    public function testBackWhenNoPreviousRoom()
    {
        /**
         * @var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject $entityManager
         */
        $entityManager = $this
            ->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        /**
         * @var PlayerService $playerService
         */
        $playerService = $this->getMockBuilder(PlayerService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $lifecycleService = new LifecycleService($entityManager, $playerService);

        $this->expectException(InvalidLifecycleException::class);
        $this->expectExceptionMessage('The player is in the starting room, thus, he cannot go back');

        $player = new Player();
        $player->setRoom(new Room());

        $lifecycleService->back($player);
    }

    public function testCollectWhenPlayerNeverVisitedRoom()
    {
        /**
         * @var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject $entityManager
         */
        $entityManager = $this
            ->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $historyRepository = $this
            ->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $entityManager
            ->method('getRepository')
            ->with(History::class)
            ->willReturn($historyRepository);
        /**
         * @var PlayerService $playerService
         */
        $playerService = $this->getMockBuilder(PlayerService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $lifecycleService = new LifecycleService($entityManager, $playerService);

        $this->expectException(InvalidLifecycleException::class);
        $this->expectExceptionMessage('The player must enter the room before collecting artifacts there');

        $lifecycleService->collect(new Player(), new Room());
    }

    public function testCollectWhenUnresolvedChallengeInRoom()
    {
        /**
         * @var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject $entityManager
         */
        $entityManager = $this
            ->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $historyRepository = $this
            ->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $history = new History();

        $historyRepository->method('findOneBy')->willReturn($history);

        $entityManager
            ->method('getRepository')
            ->with(History::class)
            ->willReturn($historyRepository);

        /**
         * @var PlayerService $playerService
         */
        $playerService = $this->getMockBuilder(PlayerService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $lifecycleService = new LifecycleService($entityManager, $playerService);

        $this->expectException(InvalidLifecycleException::class);
        $this->expectExceptionMessage('The player cannot collect artifacts in this room until he solves the challenge');

        $room = new Room();

        $room->setId(1);

        $player = new Player();
        $player->setRoom($room);

        $room->setChallenge(new Challenge());

        $lifecycleService->collect($player, $room);
    }

    public function testCollectWhenGuardInRoom()
    {
        /**
         * @var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject $entityManager
         */
        $entityManager = $this
            ->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $historyRepository = $this
            ->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $history = new History();


        $historyRepository->method('findOneBy')->willReturn($history);

        $entityManager
            ->method('getRepository')
            ->with(History::class)
            ->willReturn($historyRepository);

        /**
         * @var PlayerService $playerService
         */
        $playerService = $this->getMockBuilder(PlayerService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $lifecycleService = new LifecycleService($entityManager, $playerService);

        $this->expectException(InvalidLifecycleException::class);
        $this->expectExceptionMessage('The player cannot collect artifacts in this room until he kills the guard');

        $room = new Room();

        $room->setId(1);

        $player = new Player();
        $player->setRoom($room);

        $room->setGuard(new Guard());

        $lifecycleService->collect($player, $room);
    }
}