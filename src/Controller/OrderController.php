<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\ShoppingCart;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/cart/product/{slug}", name="order_add_product_to_cart")
     * @Method("POST")
     */
    public function addProductToCartAction(Product $product, ShoppingCart $cart)
    {
        $cart->addProduct($product);

        $this->addFlash('success', 'Product added.');
        return $this->redirectToRoute('shop');
    }

    /**
     * @Route("/cart/order-review", name="order_review")
     */
    public function orderReview(ShoppingCart $cart)
    {
        $breadcrumbs = ['Shop','Cart','Review'];
        $title = 'Cart Review';
        $products = $cart->getProducts();
        return $this->render('shop/cart/order_review.html.twig', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $title,
            'cart' => $cart,
            'products' => $products
        ]);

    }

    public function checkoutAction()
    {

    }
}
