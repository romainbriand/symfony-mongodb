<?php

namespace App\Controller;

use App\Document\Restaurant;
use App\Form\Type\RestaurantType;
use App\Repository\RestaurantRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Cache\InvalidArgumentException;

/**
 * Class AdminRestaurantController
 *
 * @package App\Controller
 */
class AdminRestaurantController extends AbstractController
{
    /** @var DocumentManager $documentManager */
    protected $documentManager;


    protected $cache;

    /**
     * AdminRestaurantController constructor
     *
     * @param DocumentManager  $documentManager
     * @param AdapterInterface $cache
     */
    public function __construct(DocumentManager $documentManager, AdapterInterface $cache)
    {
        $this->documentManager = $documentManager;
        $this->cache           = $cache;
    }

    /**
     * @Route("/admin/restaurant/{page}", name="admin_restaurant", requirements={"page"="\d+"}, defaults={"page"=1})
     * @Template()
     *
     * @param int                  $page
     * @param RestaurantRepository $restaurantRepository
     * @param LoggerInterface      $logger
     *
     * @return array|HttpException
     */
    public function indexAction($page, RestaurantRepository $restaurantRepository, LoggerInterface $logger)
    {
        try {
            $restaurants = $restaurantRepository->findAllOrderedById($page);
            $total       = $restaurantRepository->countAll();

            $hasPrev = ($page > 1);
            $hasNext = ($page * $restaurantRepository::LIMIT) < $total;

            return [
                "restaurants" => $restaurants,
                "page"        => $page,
                "hasPrev"     => $hasPrev,
                "hasNext"     => $hasNext
            ];
        } catch (\Exception $exception) {
            $logger->debug($exception->getMessage(), $exception->getTrace());

            return new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, "Erreur");
        }
    }

    /**
     * @Route("/admin/restaurant/add", name="admin_restaurant_add")
     * @Template()
     *
     * @param Request $request
     *
     * @return RedirectResponse|array
     * @throws MongoDBException
     * @throws InvalidArgumentException
     */
    public function addAction(Request $request)
    {
        $restaurant = new Restaurant();
        $form       = $this->createForm(RestaurantType::class, $restaurant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $restaurant = $form->getData();
            $this->documentManager->persist($restaurant);
            $this->documentManager->flush();
            $this->cache->deleteItem("all_restaurants"); // On invalide le cache de la liste des restaurants

            return $this->redirectToRoute('admin_restaurant');
        }

        return [
            "form" => $form->createView()
        ];
    }

    /**
     * @Route("/admin/restaurant/edit/{restaurant}", name="admin_restaurant_edit")
     * @Template()
     *
     * @param Restaurant $restaurant
     * @param Request    $request
     *
     * @return RedirectResponse|array
     * @throws MongoDBException
     * @throws InvalidArgumentException
     */
    public function editAction(Restaurant $restaurant, Request $request)
    {
        $form = $this->createForm(RestaurantType::class, $restaurant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $restaurant = $form->getData();
            $this->documentManager->persist($restaurant);
            $this->documentManager->flush();
            $this->cache->deleteItem("all_restaurants"); // On invalide le cache de la liste des restaurants

            return $this->redirectToRoute('admin_restaurant');
        }

        return [
            "form" => $form->createView()
        ];
    }

    /**
     * @Route("/admin/restaurant/delete/{restaurant}", name="admin_restaurant_delete")
     * @Template()
     *
     * @param Restaurant      $restaurant
     * @param DocumentManager $documentManager
     *
     * @return RedirectResponse
     * @throws MongoDBException
     * @throws InvalidArgumentException
     *
     */
    public function deleteAction(Restaurant $restaurant, DocumentManager $documentManager)
    {
        $documentManager->remove($restaurant);
        $documentManager->flush();
        $this->cache->deleteItem("all_restaurants"); // On invalide le cache de la liste des restaurants

        return $this->redirectToRoute('admin_restaurant');
    }
}