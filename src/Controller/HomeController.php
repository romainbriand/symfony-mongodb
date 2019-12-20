<?php

namespace App\Controller;

use App\Document\Restaurant;
use Doctrine\ODM\MongoDB\DocumentManager;
use Psr\Cache\InvalidArgumentException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Route("/points", name="points")
     * @Template()
     *
     * @param DocumentManager  $documentManager
     * @param AdapterInterface $cache
     *
     * @return JsonResponse
     * @throws InvalidArgumentException
     */
    public function pointsAction(DocumentManager $documentManager, AdapterInterface $cache)
    {
        $cachedRestaurant = $cache->getItem("all_restaurants");
        if (!$cachedRestaurant->isHit()) {
            $cachedRestaurant->set($documentManager->getRepository(Restaurant::class)
                                                   ->findAll());
            $cache->save($cachedRestaurant); // La liste des restaurants est mis en cache (sur Redis)
        }

        return new JsonResponse($cachedRestaurant->get());
    }
}