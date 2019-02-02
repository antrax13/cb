<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    /**
     * @Route("/shop", name="shop")
     */
    public function index(CategoryRepository $repository)
    {
        $breadcrumbs = ['Shop'];

        $categories = $repository->findAll();

        return $this->render('shop/index.html.twig', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $breadcrumbs[0],
            'categories' => $categories,
        ]);
    }

    public function showCategoryProducts(Category $category)
    {
        $products = $category->getProduct();
        $breadcrumbs = ['Shop', 'Categories'];

        return $this->render('shop/categories.html.twig', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $breadcrumbs[0] .' | '.$breadcrumbs[1],
            'products' => $products,
        ]);


    }

    /**
     * @Route("/shop/{category}/{product}", name="show_product_details")
     */
    public function showProduct(Category $category, Product $product)
    {
        $breadcrumbs = ['Shop',$product->getCategory()->getName(),$product->getName()];

        return $this->render('shop/product/show.html.twig', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $breadcrumbs[0].' | '.$breadcrumbs[2],
            'product' => $product,
        ]);
    }

    /*
     *
     */
    public function editProduct(Product $product)
    {

    }
}
