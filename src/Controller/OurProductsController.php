<?php

namespace App\Controller;

use App\Repository\CustomProductInfoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OurProductsController extends AbstractController
{
    /**
     * @Route("/our-products", name="our_products")
     */
    public function index(CustomProductInfoRepository $repository)
    {
        $breadcrumbs = ['Our Products'];
        $items = $repository->findAll();

        return $this->render('our_products/index.html.twig', [
            'title' => 'Our Products',
            'breadcrumbs' => $breadcrumbs,
            'items' => $items
        ]);
    }
}
