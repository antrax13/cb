<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\Shop\CategoryType;
use App\Form\Shop\ProductType;
use App\Repository\CategoryRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/shop/{slug}", name="shop_category")
     */
    public function showCategoryProducts(Category $category)
    {
        $breadcrumbs = ['Shop', $category->getName()];

        return $this->render('shop/categories.html.twig', [
            'breadcrumbs' => $breadcrumbs,
            'title' => implode(' | ', $breadcrumbs),
            'category' => $category,
        ]);
    }

    /**
     * @Route("/shop/product/{slug}", name="shop_show_product_details")
     */
    public function showProduct(Product $product)
    {
        $breadcrumbs = ['Shop',$product->getCategory()->getName(),$product->getName()];

        return $this->render('shop/product/show.html.twig', [
            'breadcrumbs' => $breadcrumbs,
            'title' => implode(' | ', $breadcrumbs),
            'product' => $product,
        ]);
    }
}
