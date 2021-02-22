<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\Shop\CategoryType;
use App\Form\Shop\ProductType;
use App\Repository\CategoryRepository;
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
        $breadcrumbs = [
            [
                'name' => 'Shop',
                'url' => $this->generateUrl('shop')
            ]
        ];

        $categories = $repository->findAll();

        return $this->render('shop/index.html.twig', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $breadcrumbs[0]['name'],
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/shop/{slug}", name="shop_category")
     */
    public function showCategoryProducts(Category $category)
    {
        $breadcrumbs = [
            [
                'name' => 'Shop',
                'url' => $this->generateUrl('shop')
            ],
            [
                'name' => $category->getName(),
                'url' => $this->generateUrl('shop_category', [
                    'slug' => $category->getSlug()
                ])
            ]
        ];

        return $this->render('shop/categories.html.twig', [
            'breadcrumbs' => $breadcrumbs,
            'title' =>  $breadcrumbs[1]['name'].' | '.$breadcrumbs[0]['name'],
            'category' => $category,
        ]);
    }

    /**
     * @Route("/shop/product/{slug}", name="shop_show_product_details")
     */
    public function showProduct(Product $product)
    {
        $breadcrumbs = [
            [
                'name' => 'Shop',
                'url' => $this->generateUrl('shop')
            ],
            [
                'name' => $product->getCategory()->getName(),
                'url' => $this->generateUrl('shop_category', [
                    'slug' => $product->getCategory()->getSlug()
                ])
            ],
            [
                'name' => $product->getName(),
                'url' => $this->generateUrl('shop_show_product_details', [
                    'slug' => $product->getSlug()
                ])
            ]
        ];

        return $this->render('shop/product/show.html.twig', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $breadcrumbs[2]['name'].' | '.$breadcrumbs[1]['name'].' | '.$breadcrumbs[0]['name'],
            'product' => $product,
        ]);
    }
}
