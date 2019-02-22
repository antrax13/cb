<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\Shop\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class ProductCategoryController extends AbstractController
{
    /**
     * @Route("/admin/category/new", name="admin_category_new")
     *
     */
    public function newCategory(Request $request, ObjectManager $manager)
    {
        $breadcrumbs = ['Admin', 'Product Category', 'New'];

        $form = $this->createForm(CategoryType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $category = $form->getData();
            $manager->persist($category);
            $manager->flush();


            $this->addFlash('success', 'Category <b>'.$category->getName().'</b> has been added.');
            return $this->redirectToRoute('admin_product_category');
        }


        return $this->render('shop/category/new.html.twig', [
            'breadcrumbs' => $breadcrumbs,
            'title' => implode(' | ', $breadcrumbs),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/category/{category}/edit", name="admin_category_edit")
     *
     */
    public function editCategory(Category $category, Request $request, ObjectManager $manager)
    {
        $breadcrumbs = ['Admin', $category->getName(), 'Edit'];

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($category);
            $manager->flush();


            $this->addFlash('success', 'Category <b>'.$category->getName().'</b> has been updated.');
            return $this->redirectToRoute('admin_product_category');
        }


        return $this->render('shop/category/edit.html.twig', [
            'breadcrumbs' => $breadcrumbs,
            'title' => implode(' | ', $breadcrumbs),
            'form' => $form->createView(),
            'products' => $category->getProduct(),
        ]);
    }

    /**
     * @Route("admin/product-category", name="admin_product_category")
     */
    public function showProductCategories(CategoryRepository $repository)
    {
        $breadcrumbs = ['Admin', 'Product Categories'];
        $categories = $repository->findAll();

        $categoryArray = [];
        foreach($categories as $category){
            $categoryArray[] = [
                'id' => $category->getId(),
                'is_active' => $category->getIsActive(),
                'name' => $category->getName(),
                'product_count' => count($category->getProduct()),
                'updated_at' => $category->getUpdatedAt()->format('Y-m-d'),
                'updated_by' => $category->getUpdatedBy(),
            ];
        }

        return $this->render('admin/category.html.twig',[
            'title' => implode(', ', $breadcrumbs),
            'breadcrumbs' => $breadcrumbs,
            'records' => $categoryArray,
        ]);
    }
}
