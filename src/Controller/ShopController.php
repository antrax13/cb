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
     * @Route("/shop/{category}", name="shop_category")
     */
    public function showCategoryProducts(Category $category)
    {
        $products = $category->getProduct();
        $breadcrumbs = ['Shop', 'Categories'];

        return $this->render('shop/categories.html.twig', [
            'breadcrumbs' => $breadcrumbs,
            'title' => explode(' | ', $breadcrumbs),
            'products' => $products,
        ]);
    }


    /**
     * @Route("/shop/{category}/edit", name="shop_category_edit")
     *
     */
    public function newCategory(Request $request, Category $category, ObjectManager $manager)
    {
        $breadcrumbs = ['Shop', $category->getName(), 'Edit'];

        $form = $this->createForm(CategoryType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($category);
            $manager->flush();


            $this->addFlash('success', 'Category <b>'.$category->getName().'</b> has been added.');
            return $this->redirectToRoute('shop_category', ['category' => $category->getId()]);
        }


        return $this->render('shop/category/edit.html.twig', [
            'breadcrumbs' => $breadcrumbs,
            'title' => explode(' | ', $breadcrumbs),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/shop/{product}", name="show_product_details")
     */
    public function showProduct(Product $product)
    {
        $breadcrumbs = ['Shop',$product->getCategory()->getName(),$product->getName()];

        return $this->render('shop/product/show.html.twig', [
            'breadcrumbs' => $breadcrumbs,
            'title' => explode(' | ', $breadcrumbs),
            'product' => $product,
        ]);
    }

    /**
     * @Route("/shop/{product}/edit", name="shop_product_edit_details")
     */
    public function editProduct(Product $product, ObjectManager $manager)
    {
        $breadcrumbs = ['Shop', $product->getName(), 'Edit'];

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($product);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($product);
            $manager->flush();

            $this->addFlash('success', 'Product <b>'.$product->getName().'</b> has been updated.');
            $this->redirectToRoute('show_product_details', ['product' => $product->getId()]);
        }



        return $this->render('shop/product/edit.html.twig', [
            'breadcrumbs' => $breadcrumbs,
            'title' => explode(' | ', $breadcrumbs),
            'product' => $product
        ]);
    }

    public function newProduct(Request $request, ObjectManager $manager)
    {
        $breadcrumbs = ['Shop','Product','New'];

        $form = $this->createForm(ProductType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            /** @var Product $product */
            $product = $form->getData();
            $manager->persist($product);
            $manager->flush();

            $this->addFlash('success', 'Product <b>'.$product->getName().'</b> has been added.');
            return $this->redirectToRoute('show_product_details', ['product' => $product->getId()]);
        }


        return $this->render('shop/product/new.html.twig',[
            'breadcrumbs' => $breadcrumbs,
            'title' => explode(' | '. $breadcrumbs),
            'form' => $form->createView(),
        ]);
    }
}
