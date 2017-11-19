<?php
namespace GameBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use GameBundle\Entity\Challenge;
use GameBundle\Entity\Guard;
use GameBundle\Entity\Health;
use GameBundle\Entity\Hint;
use GameBundle\Entity\Room;
use GameBundle\Entity\Solution;
use GameBundle\Entity\Weapon;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class InitialFixture extends Fixture
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $key = new Solution();
        $key->setDescription('Some key for some door.');

        $manager->persist($key);
        $manager->flush();

        $challenge1 = new Challenge();
        $challenge1->setDescription("You need a key to open this door. Don't you think you can get the money just like that :)");
        $challenge1->setSolution($key);

        $manager->persist($challenge1);
        $manager->flush();

        $key->setChallenge($challenge1);

        $manager->flush();


        // ROOM #1

        $room1 = new Room();
        $room1->setName('Reception');

        $room1->setInitial(true);

        $manager->persist($room1);
        $manager->flush();

        $manager->flush();

        // ROOM #2

        $room2 = new Room();
        $room2->setName('Horrible Place');
        $room2->setPreviousRoom($room1);

        $manager->persist($room2);
        $manager->flush();

        $key->setRoom($room2);

        $manager->flush();

        // ROOM #3

        $room3 = new Room();
        $room3->setName('Block A');

        $room3->setPreviousRoom($room1);

        $manager->persist($room3);
        $manager->flush();

        // ROOM #4

        $room4 = new Room();
        $room4->setName('Hall');
        $room4->setPreviousRoom($room2);

        $manager->persist($room4);
        $manager->flush();

        // ROOM #5

        $room5 = new Room();
        $room5->setName('Bank of Universe');
        $room5->setPreviousRoom($room4);

        $manager->persist($room5);
        $manager->flush();

        $challenge1->setRoom($room5);

        $manager->flush();

        $money = new Solution();
        $money->setDescription('$1 000 000 to buy a good gun.');
        $money->setRoom($room5);

        $manager->persist($money);
        $manager->flush();

        $challenge2 = new Challenge();
        $challenge2->setDescription('Do you have enough money to buy a serious gun?');

        $manager->persist($challenge2);
        $manager->flush();

        $money->setChallenge($challenge2);

        $manager->flush();

        // ROOM #6

        $room6 = new Room();
        $room6->setName('Right Way');
        $room6->setPreviousRoom($room4);

        $manager->persist($room6);
        $manager->flush();

        // ROOM #7

        $room7 = new Room();
        $room7->setName('Pharmacy');
        $room7->setPreviousRoom($room4);

        $manager->persist($room7);
        $manager->flush();

        $health = new Health();
        $health->setRoom($room7);
        $health->setPercentage(50);
        $health->setDescription('This will help you to kill the guard.');

        $manager->persist($health);
        $manager->flush();

        // ROOM #8

        $room8 = new Room();
        $room8->setName('Fight Club');
        $room8->setPreviousRoom($room6);

        $manager->persist($room8);
        $manager->flush();

        $guard = new Guard();
        $guard->setName('Big Dude');
        $guard->setRoom($room8);
        $guard->setPower(90);

        $manager->persist($guard);
        $manager->flush();

        // ROOM #9

        $room9 = new Room();
        $room9->setName('Wonderful Place');
        $room9->setPreviousRoom($room8);

        $manager->persist($room9);
        $manager->flush();

        $hint = new Hint();
        $hint->setDescription('The result of this `2 x 2 = ?` will help you in the future.');
        $hint->setRoom($room9);

        $manager->persist($hint);
        $manager->flush();

        // ROOM #10

        $room10 = new Room();
        $room10->setName('Outside World');
        $room10->setPreviousRoom($room9);

        $manager->persist($room10);
        $manager->flush();

        // ROOM #11

        $room11 = new Room();
        $room11->setName('Bridge');
        $room11->setPreviousRoom($room8);

        $manager->persist($room11);
        $manager->flush();


        // ROOM #12
        $room12 = new Room();
        $room12->setName('Gun Shop');
        $room12->setPreviousRoom($room11);

        $manager->persist($room12);
        $manager->flush();

        $gun = new Weapon();
        $gun->setPower(100);
        $gun->setDescription('A super gun to kill a super bad guy.');
        $gun->setRoom($room12);

        $manager->persist($gun);

        $challenge2->setRoom($room12);

        $manager->flush();

        // ROOM #13

        $room13 = new Room();
        $room13->setName('Scary Holes');
        $room13->setPreviousRoom($room11);

        $manager->persist($room13);
        $manager->flush();

        $health = new Health();
        $health->setPercentage(50);
        $health->setDescription('This should make you feel much better.');
        $health->setRoom($room13);

        $manager->persist($health);
        $manager->flush();

        for ($i = 1; $i <= 10; $i ++){

            $roomX = new Room();
            $roomX->setName('Hole #' . $i);
            $roomX->setPreviousRoom($room13);

            $manager->persist($roomX);
            $manager->flush();

            if ($i == 4){
                $guard = new Guard();
                $guard->setName('Killer #1');
                $guard->setPower(140);
                $guard->setRoom($roomX);

                $manager->persist($guard);

                $roomX->setFinal(true);

                $manager->flush();
            }
        }
    }
}