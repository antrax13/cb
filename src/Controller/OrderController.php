<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/cart/product/{slug}", name="order_add_product_to_cart")
     */
    public function addProductToCartAction(Product $product)
    {

        $this->addFlash('success', 'Product added.');
        return $this->redirectToRoute('shop');
    }
}
