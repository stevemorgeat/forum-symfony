<?php
/**
 * Created by PhpStorm.
 * User: allth
 * Date: 06/09/2017
 * Time: 09:49
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Author;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AuthorFixture extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $encoderFactory = $this->container->get("security.encoder_factory");
        $encoder = $encoderFactory->getEncoder(new Author());
        $password = $encoder->encodePassword("123",null);

        // auteur 1
        $author = new Author();
        $author->setName("Hugo")
                ->setFirstName("Victor")
               ->setEmail("v.hugo@miserable.fr")
                ->setPassword($password);

        $this->addReference("auteur_1", $author);
        $manager->persist($author);

        //auteur 2

        $author = new Author();
        $author->setName("Bernard")
            ->setFirstName("Jo")
            ->setEmail("j.bernard@plum.fr")
            ->setPassword($password);

        $this->addReference("auteur_2", $author);
        $manager->persist($author);

        // auteur 3

        $author = new Author();
        $author->setName("Papin")
            ->setFirstName("Jean Pierre")
            ->setEmail("jpp@reprise.fr")
            ->setPassword($password);

        $this->addReference("auteur_3", $author);
        $manager->persist($author);

        // auteur 4

        $author = new Author();
        $author->setName("Pastore")
            ->setFirstName("Javier")
            ->setEmail("j.pastore@psg.fr")
            ->setPassword($password);

        $this->addReference("auteur_4", $author);
        $manager->persist($author);

        //auteur 5
        $author = new Author();
        $author->setName("Lo Celso")
            ->setFirstName("Giovanni")
            ->setEmail("g.lo_celso@psg.fr")
            ->setPassword($password);

        $this->addReference("auteur_5", $author);
        $manager->persist($author);

        // auteur 6

        $author = new Author();
        $author->setName("Verratti")
            ->setFirstName("Marco")
            ->setEmail("m.verratti@psg.fr")
            ->setPassword($password);

        $this->addReference("auteur_6", $author);
        $manager->persist($author);


        // auteur 7

        $author = new Author();
        $author->setName("Jr")
            ->setFirstName("Neymar")
            ->setEmail("jr.neymar@psg.fr")
            ->setPassword($password);

        $this->addReference("auteur_7", $author);
        $manager->persist($author);


        // auteur 8

        $author = new Author();
        $author->setName("Thiago")
            ->setFirstName("Silva")
            ->setEmail("t.silva@psg.fr")
            ->setPassword($password);

        $this->addReference("auteur_8", $author);
        $manager->persist($author);


        // auteur 9

        $author = new Author();
        $author->setName("Julian")
            ->setFirstName("Draxler")
            ->setEmail("j.draxler@psg.fr")
            ->setPassword($password);

        $this->addReference("auteur_9", $author);
        $manager->persist($author);


        // auteur 10

        $author = new Author();
        $author->setName("Edinson")
            ->setFirstName("Cavani")
            ->setEmail("e.cavani@psg.fr")
            ->setPassword($password);

        $this->addReference("auteur_10", $author);
        $manager->persist($author);

        // auteur 11

        $author = new Author();
        $author->setName("Kylian")
            ->setFirstName("Mbappe")
            ->setEmail("k.mbappe@psg.fr")
            ->setPassword($password);

        $this->addReference("auteur_11", $author);
        $manager->persist($author);


        // auteur 12

        $author = new Author();
        $author->setName("Angel")
            ->setFirstName("Di Maria")
            ->setEmail("a.dimaria@psg.fr")
            ->setPassword($password);

        $this->addReference("auteur_12", $author);
        $manager->persist($author);


        // auteur 13

        $author = new Author();
        $author->setName("Adrien")
            ->setFirstName("Rabiot")
            ->setEmail("a.rabiot@psg.fr")
            ->setPassword($password);

        $this->addReference("auteur_13", $author);
        $manager->persist($author);

        // auteur 14
        $author = new Author();
        $author->setName("Blaise")
            ->setFirstName("Matuidi")
            ->setEmail("b.matuidi@psg.fr")
            ->setPassword($password);

        $this->addReference("auteur_14", $author);
        $manager->persist($author);

        // auteur 15

        $author = new Author();
        $author->setName("David")
            ->setFirstName("Beckham")
            ->setEmail("d.beckham@psg.fr")
            ->setPassword($password);

        $this->addReference("auteur_15", $author);
        $manager->persist($author);

        // auteur 16

        $author = new Author();
        $author->setName("Zlatan")
            ->setFirstName("Ibrahimovic")
            ->setEmail("z.ibrahimovic@psg.fr")
            ->setPassword($password);

        $this->addReference("auteur_16", $author);
        $manager->persist($author);

        // auteur 17

        $author = new Author();
        $author->setName("Thomas")
            ->setFirstName("Meunier")
            ->setEmail("t.meunier@psg.fr")
            ->setPassword($password);

        $this->addReference("auteur_17", $author);
        $manager->persist($author);

        // auteur 18

        $author = new Author();
        $author->setName("Ophélie")
            ->setFirstName("Meunier")
            ->setEmail("o.meunier@m6.fr")
            ->setPassword($password);

        $this->addReference("auteur_18", $author);
        $manager->persist($author);


        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 3;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}