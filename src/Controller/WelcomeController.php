<?php

namespace App\Controller;

use App\Repository\CustomProductInfoRepository;
use App\Repository\GalleryPhotoRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WelcomeController extends AbstractController
{
    /**
     * @Route("/", name="welcome")
     */
    public function index(ProductRepository $repository, CustomProductInfoRepository $infoRepository, GalleryPhotoRepository $galleryPhotoRepository)
    {
        $products = $repository->findBy([
            'isActive' => true,
        ]);

        $customItems = $infoRepository->findBy([
            'isFeatured' => true,
        ]);

        $productsArray = [];
        if(count($products) > 0) {
            $noProducts = 4;
            $randomNumbers = array_rand($products, $noProducts);
            foreach ($randomNumbers as $number) {
                $productsArray[] = $products[$number];
            }
        }

        $productsCustomArray = [];
        if(count($customItems) > 0) {
            $noProductsCustom = 2;
            $randomCustomNumbers = array_rand($customItems, $noProductsCustom);
            foreach ($randomCustomNumbers as $number) {
                $productsCustomArray[] = $customItems[$number];
            }
        }
        $galleryPhotos = $galleryPhotoRepository->findAll();

        $galleryPhoto = $galleryPhotos[rand(0,count($galleryPhotos)-1)];
        $customCarouselPhoto = $customItems[rand(0, count($customItems)-1)];

        return $this->render('welcome/index.html.twig', [
            'breadcrumbs' => [],
            'title' => 'Welcome',
            'products' => $productsArray,
            'customs' => $productsCustomArray,
            'randomGallery' => $galleryPhoto,
            'randomCustomPhoto' => $customCarouselPhoto
        ]);
    }
}
