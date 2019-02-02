<?php

namespace App\Controller;

use App\Entity\Shipping;
use App\Form\ManufacturingTextFormType;
use App\Form\PaymentOptionFormType;
use App\Form\ShippingOptionType;
use App\Repository\ManufacturingTextRepository;
use App\Repository\PaymentOptionTextRepository;
use App\Repository\ShippingRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/shipping", name="admin_shipping")
     */
    public function shipping(Request $request, ObjectManager $manager, ShippingRepository $repo)
    {
        $breadcrumbs = ['Admin', 'Shipping Options'];

        $shipping = new Shipping();
        $form = $this->createForm(ShippingOptionType::class, $shipping);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $shipping = $form->getData();
            $manager->persist($shipping);
            $manager->flush();

            $this->addFlash('success', 'Shipping option has been added.');

            return $this->redirectToRoute('admin_shipping');

        }

        $records = $repo->findAll();
        $shippings = [];
        foreach($records as $shipping){
            $shippings[] = [
                'id' => $shipping->getId(),
                'name' => $shipping->getName(),
                'description' => $shipping->getDescription(),
            ];
        }

        return $this->render('admin/shipping.html.twig', [
            'title' => $breadcrumbs[1],
            'breadcrumbs' => $breadcrumbs,
            'shippings' => $shippings,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/payment", name="admin_payment")
     */
    public function payment(Request $request, ObjectManager $manager, PaymentOptionTextRepository $repo)
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
    public function manufacturing(Request $request, ObjectManager $manager, ManufacturingTextRepository $repo)
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
