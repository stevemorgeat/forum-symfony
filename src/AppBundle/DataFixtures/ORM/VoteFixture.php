<?php
/**
 * Created by PhpStorm.
 * User: allth
 * Date: 08/09/2017
 * Time: 23:39
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Vote;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class VoteFixture extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        $answerRepository = $manager->getRepository("AppBundle:Answer");
        $answers = $answerRepository->findAll();
        $choixVote = [];
        $choixVote [-1] = "dislike";
        $choixVote [1] = "like";
        foreach ($answers as $answer) {
            $nbVotants = mt_rand(10, 18);
            for ($i = 1; $i <= $nbVotants; $i++) {
                $author_reference = "auteur_$i";
                $vote = new Vote();
                $vote->setAuthor($this->getReference($author_reference))
                    ->setAnswer($answer)
                    ->setVote(array_rand($choixVote,1));
                $manager->persist($vote);
            }


        }
        $manager->flush();

    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 50;
    }
}