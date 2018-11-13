<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerFormType;
use App\Repository\CustomerRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    /**
     * @Route("/customers", name="customers")
     */
    public function index(CustomerRepository $repository)
    {
        $breadcrumbs = ['Customers'];
        $customers = $repository->findAll();

        dump($customers);
        $array = [];
        foreach($customers as $customer){
            $array[] = [
                'id' => $customer->getId(),
                'name' => $customer->getName(),
                'email' => $customer->getEmail(),
                'quotes' => count($customer->getQuotesNotRemoved()),
            ];
        }

        dump($array);

        return $this->render('customer/index.html.twig', [
            'title' => 'Customers',
            'customers' => $array,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    /**
     * @Route("/customer/new", name="customer_new")
     */
    public function newAction(Request $request, ObjectManager $manager){

        $breadcrumbs = ['Customers', 'New'];
        $form = $this->createForm(CustomerFormType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $customer = $form->getData();

            $manager->persist($customer);
            $manager->flush();

            $this->addFlash('success', 'Customer has been created');
            return $this->redirectToRoute('customers');
        }

        return $this->render('customer/new.html.twig', [
            'title' => 'New Customer',
            'form' => $form->createView(),
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    /**
     * @Route("/customer/{id}", name="customer_show")
     */
    public function showAction(Request $request, Customer $customer, ObjectManager $manager){

        $breadcrumbs = ['Customers', $customer->getName()];

        $form = $this->createForm(CustomerFormType::class, $customer);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $customer = $form->getData();

            $manager->persist($customer);
            $manager->flush();

            $this->addFlash('success', 'Customer has been updated');
            return $this->redirectToRoute('customers');
        }

        return $this->render('customer/show.html.twig', [
            'title' => $customer->getName(),
            'form' => $form->createView(),
            'breadcrumbs' => $breadcrumbs,
            'customer' => $customer,
        ]);
    }

    /**
     * @Route("/customer/{id}/quotes", name="customer_show_request")
     */
    public function showCustomerQuotes(Customer $customer)
    {
        $breadcrumbs = ['Customers', $customer->getName(), 'Quotes'];

        $array = [];
        foreach($customer->getQuotesNotRemoved() as $quote){
            $array[] = [
                'id' => $quote->getId(),
                'quote_id' => '#CBQ00'.$quote->getId(),
                'is_removed' => $quote->getIsRemoved(),
                'request' => $quote->getRequest(),
                'customer' => $quote->getCustomer()->getEmail(),
                'sketches' => count($quote->getBrandSketchesNotRemoved()),
                'status' => $quote->getStatus(),
            ];
        }

        return $this->render('customer/quotes.html.twig', [
            'title' => $breadcrumbs[1],
            'breadcrumbs' => $breadcrumbs,
            'customer' => $customer,
            'quotes' => $array
        ]);

    }
}
