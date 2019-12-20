<?php

namespace App\DataFixtures;

use App\Document\Coordinates;
use App\Document\Restaurant;
use Doctrine\Bundle\MongoDBBundle\Fixture\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\MongoDB\DocumentManager;
use Faker\Factory;
use Faker\ORM\Doctrine\Populator;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class AppFixtures extends Fixture
{
    /** @var int Number of restaurants to generate */
    const numberOfRestaurants = 40;

    /** @var DocumentManager $documentManager */
    private $documentManager;

    /** @var AdapterInterface $cache */
    private $cache;

    /**
     * AppFixtures constructor
     *
     * @param DocumentManager $documentManager
     */
    public function __construct(DocumentManager $documentManager, AdapterInterface $cache)
    {
        $this->documentManager = $documentManager;
        $this->cache           = $cache;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $generator = Factory::create("fr_FR");
        $populator = new Populator($generator, $this->documentManager);
        $populator->addEntity(Restaurant::class, self::numberOfRestaurants, [
            'name'        => function () use ($generator) {
                return $generator->company;
            },
            'address'     => function () use ($generator) {
                return $generator->streetAddress;
            },
            'coordinates' => function () use ($generator) {
                $coordinates = new Coordinates();
                $coordinates->setLatitude($generator->randomFloat(6, 43.243113520738326, 43.351557878693775));
                $coordinates->setLongitude($generator->randomFloat(6, 5.3497827197995775, 5.441449895092546));

                return $coordinates;
            }
        ]);
        $populator->execute();

        // On supprime le cache s'il y avait déjà des restaurants en cache précédemment
        $this->cache->deleteItem("all_restaurants");
    }
}
