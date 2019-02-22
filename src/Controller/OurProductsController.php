<?php

namespace App\Controller;

use App\Entity\CustomProductInfo;
use App\Form\Admin\CustomProductInfoType;
use App\Repository\CustomProductInfoRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/our-products/{slug}", name="our_products_show")
     */
    public function showAction(CustomProductInfo $productInfo)
    {
        $breadcrumbs = ['Our Products', $productInfo->getType()];
        return $this->render('our_products/show.html.twig', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $breadcrumbs[1],
            'product' => $productInfo
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/custom-products", name="admin_custom_products")
     */
    public function adminAction(CustomProductInfoRepository $repository)
    {
        $breadcrumbs = ['Admin', 'Custom Products'];
        $products = $repository->findAll();
        $array = [];
        foreach($products as $product){
            $array[] = [
                'id' => $product->getId(),
                'type' => $product->getType(),
                'intro' => $product->getIntro()
            ];
        }

        return $this->render('our_products/admin/index.html.twig', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $breadcrumbs[1],
            'records' => $array,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/custom-products/new", name="admin_custom_products_new")
     */
    public function newAction(Request $request, ObjectManager $manager)
    {
        $breadcrumbs = ['Admin','Custom Products', 'New'];

        $form = $this->createForm(CustomProductInfoType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $product = $form->getData();
            /** @var UploadedFile $file */
            $file = $form->get('image')->getData();
            if($file){
                $fileExtension = $file->guessExtension();
                $fileName = md5(uniqid()) . '.' . $fileExtension;

                // moves the file to the directory where tags are stored
                $file->move(
                    $this->getParameter('custom_product_dir'),
                    $fileName
                );
                $product->setImage($fileName);
            }

            $product->setCreatedBy($this->getUser()->getUsername());
            $product->setUpdatedBy($this->getUser()->getUsername());
            $manager->persist($product);
            $manager->flush();

            $this->addFlash('success', 'Custom Product has been added.');
            return $this->redirectToRoute('admin_custom_products');
        }

        return $this->render('our_products/admin/new.html.twig', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $breadcrumbs[1],
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/custom-products/{id}/edit", name="admin_custom_products_edit")
     */
    public function editAction(Request $request, CustomProductInfo $productInfo, ObjectManager $manager)
    {
        $breadcrumbs = ['Admin','Custom Products', $productInfo->getType(), 'Edit'];

        $image = $productInfo->getImage();
        $productInfo->setImage(new File($this->getParameter('custom_product_dir') . '/' . $productInfo->getImage()));


        $form = $this->createForm(CustomProductInfoType::class, $productInfo);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){
            $product = $form->getData();
            /** @var UploadedFile $file */
            $file = $form->get('image')->getData();
            if($file instanceof UploadedFile){
                $fileExtension = $file->guessExtension();
                $fileName = md5(uniqid()) . '.' . $fileExtension;

                // moves the file to the directory where tags are stored
                $file->move(
                    $this->getParameter('custom_product_dir'),
                    $fileName
                );
                $product->setImage($fileName);
            }else{
                $product->setImage($image);
            }
            $manager->persist($product);
            $manager->flush();

            $this->addFlash('success', 'Custom Product has been updated.');
            return $this->redirectToRoute('admin_custom_products');
        }

        return $this->render('our_products/admin/edit.html.twig', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $breadcrumbs[1],
            'form' => $form->createView(),
            'product' => $productInfo
        ]);
    }
}
