<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Entity\InvoiceItem;
use App\Form\InvoiceEditFormType;
use App\Form\InvoiceItemEditFormType;
use App\Form\InvoiceItemNewFormType;
use App\Form\InvoiceItemPaypalType;
use App\Form\InvoiceItemShippingType;
use App\Form\InvoiceNewFormType;
use App\Repository\BrandSketchRepository;
use App\Repository\InvoiceItemRepository;
use App\Repository\InvoiceRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class InvoiceController extends AbstractController
{
    /**
     * @Route("/invoices", name="invoices")
     */
    public function index(InvoiceRepository $repository)
    {
        $breadcrumbs = ['Invoices'];
        $invoices = $repository->findAll();

        $array = [];
        foreach($invoices as $obj){
            $array[] = [
                'id' => $obj->getId(),
                'reference' => $obj->getReference(),
                'generated_date' => $obj->getGeneratedAt() ? $obj->getGeneratedAt()->format('Y-m-d') : null,
                'status' => $obj->getStatus(),
            ];
        }

        return $this->render('invoice/index.html.twig', [
            'title' => 'Invoices',
            'invoices' => $array,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    /**
     * @Route("/invoices/new", name="invoices_new")
     */
    public function newAction(Request $request, ObjectManager $manager, InvoiceRepository $repository)
    {
        $breadcrumbs = ['Invoices', 'New'];

        $invoices = $repository->findAll();

        $form = $this->createForm(InvoiceNewFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $reference = str_pad(count($invoices)+1,'5','0', STR_PAD_LEFT).'N/'.date('Y');
            $invoice = $form->getData();
            $invoice->setReference($reference);
            $invoice->setGeneratedAt(new \DateTime());
            $invoice->setStatus('CREATED');
            $manager->persist($invoice);
            $manager->flush();

            $this->addFlash('success','Invoice <strong>'.$reference.'</strong> has been created.');
            return $this->redirectToRoute('invoices');
        }

        return $this->render('invoice/new.html.twig', [
            'title' => 'New Invoice',
            'breadcrumbs' => $breadcrumbs,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/invoices/{id}", name="invoices_show")
     */
    public function showAction(Invoice $invoice)
    {
        $breadcrumbs = ['Invoice', $invoice->getReference(), 'Details'];

        return $this->render('invoice/show.html.twig', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $invoice->getReference(),
            'invoice' => $invoice,
        ]);
    }

    /**
     * @Route("/invoices/{id}/edit", name="invoices_show_edit")
     */
    public function editInvoiceDetails(Request $request, Invoice $invoice, ObjectManager $manager)
    {
        $breadcrumbs = ['Invoice', $invoice->getReference(),'Details', 'Edit'];
        $currentVat = $invoice->getVat();
        $form = $this->createForm(InvoiceEditFormType::class, $invoice);

        $form->handleRequest($request);

        dump($invoice);

        if($form->isSubmitted() && $form->isValid()){
            // recalculation is required if invoice has been update because VAT could change.
            $hasPaypal = false;
            foreach($invoice->getInvoiceItems() as $item){
                if($item->getIsPaypalItem()){
                    $hasPaypal = true;
                }
            }
            if($hasPaypal) {
                $invoice->setIsRecalculationRequired(true);
            }
            $manager->persist($invoice);
            $manager->flush();
            $this->addFlash('success', 'Invoice has been saved.');
            return $this->redirectToRoute('invoices_show', ['id' => $invoice->getId()]);
        }

        return $this->render('invoice/edit_details.html.twig', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $invoice->getReference(),
            'invoice' => $invoice,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/invoices/{id}/items", name="invoices_items")
     */
    public function orderAction(Request $request, Invoice $invoice, ObjectManager $manager, BrandSketchRepository $sketchRepository)
    {
        $breadcrumbs = ['Invoice', $invoice->getReference(), 'Items'];

        $sketches = $sketchRepository->findAllNotRemoved();
        $sketchesArray = [];
        foreach($sketches as $obj){
            $sketchesArray[] = [
                'id' => str_pad($obj->getId(),'5','0', STR_PAD_LEFT),
                'quote' => '#CBQ00'.$obj->getQuote()->getId(),
                'customer_email' => $obj->getQuote()->getCustomer()->getEmail(),
                'type' => $obj->getstampType()->getValue(),
                'logo_description' => $obj->getName(),
                'shape' => $obj->getStampShape()->getValue(),
                'price' => $obj->getPrice(),
                'qty' => $obj->getQty(),
                'weight' => $obj->getWeight(),
                'size' => $obj->getDimension(),
                'handle' => $obj->getHandle()
            ];
        }

        $form = $this->createForm(InvoiceItemNewFormType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            /** @var InvoiceItem $item */
            $item = $form->getData();
            $item->setInvoice($invoice);
            $manager->persist($item);
            $manager->flush();
            $this->addFlash('success', 'Item has been added to this invoice.');
            return $this->redirectToRoute('invoices_items', ['id' => $invoice->getId()]);
        }

        return $this->render('invoice/items.html.twig', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $invoice->getReference(),
            'invoice' => $invoice,
            'form' => $form->createView(),
            'sketches' => $sketchesArray
        ]);
    }

    /**
     * @Route("/invoices/{id}/add/{sketchString}", name="invoices_add_items")
     */
    public function addItemsToInvoiceAction($sketchString, ObjectManager $manager, Invoice $invoice, BrandSketchRepository $repo)
    {
        $sketches = explode(',', $sketchString);

        foreach($sketches as $id){
            $sketch = $repo->find($id);
            $name = $sketch->getstampType()->getValue().', ItemNo #'
                .str_pad($sketch->getId(),'5','0', STR_PAD_LEFT).', '
                .$sketch->getStampShape()->getValue().' '
                .$sketch->getDimension();
            $name.= $sketch->getHandle() ? ', '.$sketch->getHandle().' handle' : '';

            $item = new InvoiceItem();
            $item->setInvoice($invoice);
            $item->setPrice($sketch->getPrice());
            $item->setQty($sketch->getQty() ? $sketch->getQty() : 1);
            $item->setName($name);
            $item->setWeightPerItem($sketch->getWeight());
            $manager->persist($item);
        }
        $hasPaypal = false;
        foreach($invoice->getInvoiceItems() as $item){
            if($item->getIsPaypalItem()){
                $hasPaypal = true;
            }
        }
        if($hasPaypal) {
            $invoice->setIsRecalculationRequired(true);
            $manager->persist($invoice);
        }
        $manager->flush();

        $this->addFlash('success', count($sketches).' item(s) have been added to this invoice.');
        return $this->redirectToRoute('invoices_review_items', ['id' => $invoice->getId()]);
    }

    /**
     * @Route("/invoices/{id}/review-items", name="invoices_review_items")
     */
    public function orderReviewAction(Request $request, Invoice $invoice, ObjectManager $manager, InvoiceItemRepository $repository)
    {
        $breadcrumbs = ['Invoice', $invoice->getReference(), 'Review Items'];

        $form = $this->createForm(InvoiceItemEditFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();

            $item = $repository->find($data['id']);
            $item->setName($data['name']);
            $item->setPrice($data['price']);
            $item->setQty($data['qty']);
            $item->setWeightPerItem($data['weightPerItem']);
            $manager->persist($item);

            $hasPaypal = false;
            foreach($invoice->getInvoiceItems() as $item){
                if($item->getIsPaypalItem()){
                    $hasPaypal = true;
                }
            }
            if($hasPaypal) {
                $invoice->setIsRecalculationRequired(true);
                $manager->persist($invoice);
            }


            $manager->flush();
            $this->addFlash('success', 'Item '.$item->getName().' has been updated.');
            return $this->redirectToRoute('invoices_review_items', ['id' => $invoice->getId()]);
        }

        $form_paypal = $this->createForm(InvoiceItemPaypalType::class);
        $form_paypal->handleRequest($request);
        if($form_paypal->isSubmitted() && $form_paypal->isValid()){
            $data = $form_paypal->getData();
            $item = new InvoiceItem();
            $item->setIsPaypalItem(true);
            $item->setQty(1);
            $item->setInvoice($invoice);
            $item->setName('Paypal fee '.$data['percentage'].'%');
            $total = 0;
            foreach($invoice->getInvoiceItems() as $invoiceItem){
                if(!$invoiceItem->getIsPaypalItem()) {
                    $total += $invoiceItem->getPrice();
                }
            }
            $totalWithVat = $total * ($invoice->getVat() / 100 + 1);

            $price = $totalWithVat * $data['percentage'] / 100;
            $item->setPrice($price);
            $manager->persist($item);

            $invoice->setIsRecalculationRequired(false);
            $manager->persist($invoice);

            $manager->flush();
            $this->addFlash('success', 'Paypal '.$item->getName().' has been added.');
            return $this->redirectToRoute('invoices_review_items', ['id' => $invoice->getId()]);
        }

        $form_shipping = $this->createForm(InvoiceItemShippingType::class);
        $form_shipping->handleRequest($request);
        if($form_shipping->isSubmitted() && $form_shipping->isValid()){
            $data = $form_shipping->getData();
            $item = new InvoiceItem();
            $item->setIsShippingItem(true);
            $item->setQty(1);
            $item->setInvoice($invoice);
            $item->setName($data['name']);
            $item->setPrice($data['price']);
            $manager->persist($item);

            $hasPaypal = false;
            foreach($invoice->getInvoiceItems() as $item){
                if($item->getIsPaypalItem()){
                    $hasPaypal = true;
                }
            }
            if($hasPaypal) {
                $invoice->setIsRecalculationRequired(true);
                $manager->persist($invoice);
            }

            $manager->flush();
            $this->addFlash('success', 'Shipping '.$item->getName().' has been added.');
            return $this->redirectToRoute('invoices_review_items', ['id' => $invoice->getId()]);

        }
        $hasPaypal = $hasShipping = false;
        foreach($invoice->getInvoiceItems() as $item){
            if($item->getIsPaypalItem()){
                $hasPaypal = true;
            }
            if($item->getIsShippingItem()){
                $hasShipping = true;
            }
        }


        return $this->render('invoice/review_items.twig', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $invoice->getReference(),
            'invoice' => $invoice,
            'form' => $form->createView(),
            'form_paypal' => $form_paypal->createView(),
            'form_shipping' => $form_shipping->createView(),
            'hasPaypal' => $hasPaypal,
            'hasShipping' => $hasShipping,
        ]);
    }

    /**
     * @Route("/item-delete/{id}", name="invoices_delete_item")
     */
    public function deleteItemAction(InvoiceItem $item, ObjectManager $manager)
    {
        $invoiceid = $item->getInvoice()->getId();

        // only require recalculation if item has been deleted and it is not paypal and it has paypal
        $hasPaypal = false;
        $invoice = $item->getInvoice();
        foreach($invoice->getInvoiceItems() as $obj){
            if($obj->getIsPaypalItem()){
                $hasPaypal = true;
            }
        }

        if($item->getIsPaypalItem()){
            $invoice->setIsRecalculationRequired(false);
            $manager->persist($invoice);
        }

        if($hasPaypal && !$item->getIsPaypalItem()) {
            $invoice->setIsRecalculationRequired(true);
            $manager->persist($invoice);
        }

        if(count($invoice->getInvoiceItems()) <= 1){
            $invoice->setIsRecalculationRequired(false);
            $manager->persist($invoice);
        }

        $manager->remove($item);
        $manager->flush();



        $this->addFlash('success', 'Item has been removed');
        return $this->redirectToRoute('invoices_review_items', ['id' => $invoiceid]);
    }

    /**
     * @Route("/invoices/{id}/preview", name="invoices_preview")
     */
    public function previewAction(Invoice $invoice)
    {
        $breadcrumbs = ['Invoices', $invoice->getReference(), 'Preview'];

        return $this->render('invoice/preview.html.twig', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $invoice->getReference(),
            'invoice' => $invoice,
        ]);
    }
}
