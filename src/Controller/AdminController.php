<?php

namespace App\Controller;

use App\Entity\Shipping;
use App\Form\ManufacturingTextFormType;
use App\Form\PaymentOptionFormType;
use App\Form\ShippingOptionType;
use App\Repository\ManufacturingTextRepository;
use App\Repository\PaymentOptionTextRepository;
use App\Repository\ShippingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/admin/payment", name="admin_payment")
     */
    public function payment(Request $request, EntityManagerInterface $manager, PaymentOptionTextRepository $repo)
    {
        $breadcrumbs = ['Admin', 'Payment Options'];

        $payment = $repo->find(1);

        $form = $this->createForm(PaymentOptionFormType::class, $payment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $payment = $form->getData();
            $manager->persist($payment);
            $manager->flush();

            $this->addFlash('success', 'Payment text has been updated.');

        }

        return $this->render('admin/payment.html.twig', [
            'title' => $breadcrumbs[1],
            'breadcrumbs' => $breadcrumbs,
            'form' => $form->createView(),
            'data' => $payment
        ]);
    }

    /**
     * @Route("/admin/manufacturing", name="admin_manufacturing")
     */
    public function manufacturing(Request $request, EntityManagerInterface $manager, ManufacturingTextRepository $repo)
    {
        $breadcrumbs = ['Admin', 'Manufacturing'];

        $manufacturing = $repo->find(1);

        $form = $this->createForm(ManufacturingTextFormType::class, $manufacturing);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $payment = $form->getData();
            $manager->persist($payment);
            $manager->flush();

            $this->addFlash('success', 'Manufacturing text has been updated.');

        }

        return $this->render('admin/manufacturing.html.twig', [
            'title' => $breadcrumbs[1],
            'breadcrumbs' => $breadcrumbs,
            'form' => $form->createView(),
            'data' => $manufacturing
        ]);
    }
}
