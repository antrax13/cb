<?php

namespace App\Controller;

use App\Entity\GalleryPhoto;
use App\Form\Admin\GalleryPhotoNewType;
use App\Repository\GalleryPhotoRepository;
use App\Service\UploaderHelper;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GalleryController extends AbstractController
{
    /**
     * @Route("/gallery", name="gallery")
     */
    public function index(GalleryPhotoRepository $repository)
    {
        $breadcrumbs = [
            [
                'name' => 'Gallery',
                'url' => $this->generateUrl('gallery')
            ]
        ];

        $photos = $repository->findAllOrdered();

        return $this->render('gallery/index.html.twig', [
            'title' => $breadcrumbs[0]['name'],
            'breadcrumbs' => $breadcrumbs,
            'images' => $photos
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/gallery", name="admin_gallery")
     */
    public function adminGallery(GalleryPhotoRepository $repository)
    {
        $photos = $repository->findAllOrdered();
        $breadcrumbs = ['Admin', 'Gallery'];

        return $this->render('gallery/admin/index.html.twig', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $breadcrumbs[1],
            'photos' => $photos
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/gallery/new", name="admin_gallery_new")
     */
    public function newAction(Request $request, ObjectManager $manager, UploaderHelper $uploaderHelper)
    {
        $breadcrumbs = ['Admin', 'Gallery', 'New'];

        $form = $this->createForm(GalleryPhotoNewType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $galleryPhoto = $form->getData();
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();
            if($file){
                $fileName = $uploaderHelper->uploadFile($file,$this->getParameter('gallery_dir'));
                $galleryPhoto->setFile($fileName);
            }

            $manager->persist($galleryPhoto);
            $manager->flush();
            $this->addFlash('success', 'Gallery Photo has been added.');
            return $this->redirectToRoute('admin_gallery');
        }

        return $this->render('gallery/admin/new.html.twig',[
            'breadcrumbs' => $breadcrumbs,
            'title' => $breadcrumbs[1],
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/gallery/{id}/delete", name="admin_gallery_delete")
     */
    public function removeAction(GalleryPhoto $galleryPhoto, ObjectManager $manager)
    {
        $file = $this->getParameter('gallery_dir').'/'.$galleryPhoto->getFile();
        if(file_exists($file)){
            unlink($file);
        }
        $manager->remove($galleryPhoto);
        $manager->flush();

        $this->addFlash('success', 'Gallery Photo has been removed.');
        return $this->redirectToRoute('admin_gallery');
    }

    /**
     * @Route("/admin/gallery/reorder", methods="POST", name="admin_gallery_reorder")
     * @IsGranted("ROLE_ADMIN")
     */
    public function reorderGallery(GalleryPhotoRepository $repository, Request $request, EntityManagerInterface $entityManager)
    {
        $orderedIds = json_decode($request->getContent(), true);

        if($orderedIds === false){
            return $this->json(['details' => 'Invalid Body'], 400);
        }

        // from (position) => id to (id) -> position
        $orderedIds = array_flip($orderedIds);
        foreach($repository->findAll() as $photo){
            $photo->setPosition($orderedIds[$photo->getId()]);
        }
        $entityManager->flush();

        return $this->json($repository->findAllOrdered(), 200, [], []);

    }


}


