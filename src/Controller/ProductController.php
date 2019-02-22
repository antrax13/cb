<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\Shop\ProductType;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @IsGranted("ROLE_ADMIN")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/admin/product/new", name="admin_product_new")
     */
    public function newProduct(Request $request, ObjectManager $manager)
    {
        $breadcrumbs = ['Shop','Product','New'];

        $form = $this->createForm(ProductType::class,null,['is_new' => true]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            /** @var Product $product */
            $product = $form->getData();
            /** @var UploadedFile $file */
            $file = $form->get('image2')->getData();
            if($file){
                $fileExtension = $file->guessExtension();
                $fileName = md5(uniqid()) . '.' . $fileExtension;

                // moves the file to the directory where tags are stored
                $file->move(
                    $this->getParameter('product_dir'),
                    $fileName
                );
                $product->setImage($fileName);
            }

            $manager->persist($product);
            $manager->flush();

            $this->addFlash('success', 'Product <b>'.$product->getName().'</b> has been added.');
            return $this->redirectToRoute('admin_product');
        }

        return $this->render('shop/product/new.html.twig',[
            'breadcrumbs' => $breadcrumbs,
            'title' => implode(' | ', $breadcrumbs),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/product/{product}/edit", name="admin_product_edit_details")
     */
    public function editProduct(Product $product, Request $request, ObjectManager $manager)
    {
        $breadcrumbs = ['Shop', $product->getName(), 'Edit'];

        $form = $this->createForm(ProductType::class, $product, ['is_new' => false]);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){
            /** @var UploadedFile $file */
            $file = $form->get('image2')->getData();
            if($file){
                $fileExtension = $file->guessExtension();
                $fileName = md5(uniqid()) . '.' . $fileExtension;

                // moves the file to the directory where tags are stored
                $file->move(
                    $this->getParameter('product_dir'),
                    $fileName
                );
                $product->setImage($fileName);
            }

            $manager->persist($product);
            $manager->flush();

            $this->addFlash('success', 'Product <b>'.$product->getName().'</b> has been updated.');
            return $this->redirectToRoute('admin_product');
        }



        return $this->render('shop/product/edit.html.twig', [
            'breadcrumbs' => $breadcrumbs,
            'title' => implode(' | ', $breadcrumbs),
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/product", name="admin_product")
     */
    public function adminShowProducts(ProductRepository $repository)
    {
        $breadcrumbs = ['Admin', 'Products'];
        $products = $repository->findAll();

        $productArray = [];
        foreach($products as $product){
            $productArray[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'category' => $product->getCategory()->getName(),
                'price' => $product->getPrice(),
                'is_active' => $product->getIsActive(),
                'updated_at' => $product->getUpdatedAt()->format('Y-m-d'),
                'updated_by' => $product->getUpdatedBy(),
            ];
        }

        return $this->render('admin/product.html.twig',[
            'title' => implode(', ', $breadcrumbs),
            'breadcrumbs' => $breadcrumbs,
            'records' => $productArray,
        ]);
    }

}
