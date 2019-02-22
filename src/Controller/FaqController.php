<?php

namespace App\Controller;

use App\Entity\FaqQuestion;
use App\Form\Admin\CreateFaqFormType;
use App\Repository\FaqCategoryRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FaqController extends AbstractController
{
    /**
     * @Route("/faq", name="front_end_faq")
     */
    public function index(FaqCategoryRepository $repository)
    {
        $breadcrumbs = ['Frequently Asked Questions'];
        $faqCategory = $repository->findAll();
        return $this->render('faq/index.html.twig', [
            'title' => $breadcrumbs[0],
            'breadcrumbs' => $breadcrumbs,
            'faq' => $faqCategory,
        ]);
    }

    /**
     * @Route("/admin/faq", name="admin_faq")
     * @IsGranted("ROLE_ADMIN")
     */
    public function adminFaq(Request $request, ObjectManager $manager, FaqCategoryRepository $repository)
    {
        $breadcrumbs = ['Admin','Frequently Asked Questions'];

        $faqCategory = $repository->findAll();

        $form = $this->createForm(CreateFaqFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $faqQuestion = $form->getData();
            $manager->persist($faqQuestion);
            $manager->flush();

            $this->addFlash('success', 'Question has been submitted.');
            return $this->redirectToRoute('admin_faq');
        }

        return $this->render('admin/faq.html.twig', [
            'title' => $breadcrumbs[1],
            'breadcrumbs' => $breadcrumbs,
            'form' => $form->createView(),
            'faq' => $faqCategory,
        ]);
    }

    /**
     * @Route("/admin/faq/{id}", name="admin_faq_edit")
     * @IsGranted("ROLE_ADMIN")
     */
    public function adminFaqEdit(FaqQuestion $faqQuestion, Request $request, ObjectManager $manager, FaqCategoryRepository $repository)
    {
        $breadcrumbs = ['Admin','Frequently Asked Questions', $faqQuestion->getId(), 'Edit'];
        $faqCategory = $repository->findAll();
        $form = $this->createForm(CreateFaqFormType::class, $faqQuestion);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $faqQuestion = $form->getData();
            $manager->persist($faqQuestion);
            $manager->flush();

            $this->addFlash('success', 'Question has been updated.');
            return $this->redirectToRoute('admin_faq');
        }

        return $this->render('admin/faq_edit.html.twig', [
            'title' => $breadcrumbs[1],
            'breadcrumbs' => $breadcrumbs,
            'form' => $form->createView(),
            'faq' => $faqCategory,
            'faqQuestion' => $faqQuestion,
        ]);
    }

    /**
     * @Route("/admin/faq/{id}/delete", name="admin_faq_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function adminFaqDelete(FaqQuestion $faqQuestion, ObjectManager $manager)
    {
        $manager->remove($faqQuestion);
        $manager->flush();
        $this->addFlash('success', 'Question has been deleted.');

        return $this->redirectToRoute('admin_faq');
    }
}
