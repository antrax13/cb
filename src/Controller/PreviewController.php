<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Entity\Quote;
use App\Repository\FedexDeliveryRepository;
use App\Repository\ManufacturingTextRepository;
use App\Repository\PaymentOptionTextRepository;
use App\Service\InvoicePDF;
use App\Service\MyPDF;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use jonasarts\Bundle\TCPDFBundle\TCPDF\TCPDF;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class PreviewController extends AbstractController
{
    /**
     * @Route("/quote/{id}/preview-and-generate-pdf", name="quote_preview")
     */
    public function showAction(Quote $quote, PaymentOptionTextRepository $paymentRepository, ManufacturingTextRepository $manufacturingRepository)
    {
        if ($quote->getIsRemoved()) {
            $this->addFlash('danger', 'Quote has been removed. Contact Administrator to update Quote: ' . $quote->getId() . ' manually.');
            return $this->redirectToRoute('quotes');
        }

        $payment = $paymentRepository->find(1);

        $manufacturing = $manufacturingRepository->find(1);

        $breadcrumbs = ['Quote', '#CBQ00' . $quote->getId(), 'Quote Preview'];


        return $this->render('quote/preview.html.twig', [
            'title' => $breadcrumbs[2],
            'breadcrumbs' => $breadcrumbs,
            'quote' => $quote,
            'paymentOption' => $payment,
            'manufacturing' => $manufacturing
        ]);
    }

    /**
     * @Route("/generate-quote/{id}", name="generate_pdf")
     */
    public function generatePDF(Quote $quote, PaymentOptionTextRepository $paymentRepository, ManufacturingTextRepository $manufacturingRepository, FedexDeliveryRepository $fedexDeliveryRepository, ObjectManager $manager)
    {
        $payment = $paymentRepository->find(1);

        $manufacturing = $manufacturingRepository->find(1);

        $shippingCodes = [];

        $totalWeight = 0;
        if($quote->getBrandSketchesNotRemoved()){
            foreach($quote->getBrandSketchesNotRemoved() as $item){
                $totalWeight+= ($item->getQty() * $item->getWeight());
//                dump($totalWeight);
            }
        }

//        dd($totalWeight);

        if ($quote->getShippingCountry()) {
            $shippingObjs = $fedexDeliveryRepository->findAll();
            $shippingCode = $quote->getShippingCountry()->getFedexCode();
            $method = 'getCode' . $shippingCode;
            $weightSet = false;
            foreach ($shippingObjs as $shippingOption) {
                if($totalWeight < $shippingOption->getWeight() && $weightSet == false) {
                    $shippingCodes[$shippingOption->getWeight()] = [
                        $shippingOption->$method()
                    ];
                    $weightSet = true;
                }
            }
        }

        $shipping = [
            'shippingCountry' => $quote->getShippingCountry(),
            'shippingWeights' => $shippingCodes
        ];

        $quote->setGeneratedAt(new \DateTime('now'));
        $quote->setStatus('PDF Generated');
        $manager->persist($quote);
        $manager->flush();
        $pdfNameKeywords = [];

        $pdfKeywords = [$quote->getCustomer()->getName(), $quote->getCustomer()->getEmail()];
        if (count($quote->getBrandSketchesNotRemoved()) > 0) {
            foreach ($quote->getBrandSketchesNotRemoved() as $sketch) {
                if ($sketch->getName()) {
                    $pdfKeywords[] = $sketch->getName();
                    $pdfNameKeywords[] = $sketch->getName();
                }
            }
        }

        $keywords = implode(', ', $pdfKeywords);

        $pdf = new MyPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
        $pdf->SetCreator('Peter Kosak');
        $pdf->SetAuthor('Peter Kosak');
        $pdf->SetTitle('#CBQ00' . $quote->getId() . '-Cocktail Brandalism');
        $pdf->SetSubject('Quote');
        $pdf->SetKeywords($keywords);

// set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
        $pdf->SetMargins(15, 50, 15);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->setPrintHeader(true);
        $pdf->setPrintFooter(true);

        // set font
        $pdf->SetFont('helvetica', '', 12);

        $pdf->AddPage();
        $pdf->setRasterizeVectorImages(false);

        $pdfHtml = $this->renderView('partials/_print.html.twig', [
            'quote' => $quote,
            'shipping' => $shipping,
            'paymentOption' => $payment,
            'manufacturing' => $manufacturing,
            'totalWeight' => $totalWeight
        ]);

        $html = <<<EOF
$pdfHtml
EOF;

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('#CBQ00' . $quote->getId() . '-' . implode(' ', array_unique($pdfNameKeywords)) . '-CocktailBrandalism.pdf', 'I');
    }

    /**
     * @Route("/generate-invoice/{id}", name="generate_invoice_pdf")
     */
    public function generateInvoicePDF(Invoice $invoice)
    {

        $pdf = new InvoicePDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
//        $pdf->setHeaderData($ln='', $lw=0, $ht='', $hs='<table cellspacing="0" cellpadding="1" border="1">tr><td rowspan="3">test</td><td>test</td></tr></table>', $tc=array(0,0,0), $lc=array(0,0,0));

        $pdf->setInvoiceReference($invoice->getReference());

// set document information
        $pdf->SetCreator('Peter Kosak');
        $pdf->SetAuthor('Peter Kosak');
        $pdf->SetTitle('Cocktail Brandalism Invoice' . $invoice->getReference());
        $pdf->SetSubject('Proforma Invoice');
        $pdf->SetKeywords('Cocktail Brandalism');

// set default header data
//        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
        $pdf->SetMargins(15, 40, 15);
        $pdf->SetHeaderMargin(15);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->setPrintHeader(true);
        $pdf->setPrintFooter(true);

        // set font
        $pdf->SetFont('helvetica', '', 12);

        $pdf->AddPage();

        $pdfHtml = $this->renderView('invoice/partials/_print.html.twig', [
            'invoice' => $invoice,
        ]);

        $html = <<<EOF
$pdfHtml
EOF;

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('CocktailBrandalism-ProformaInvoice' . $invoice->getReference() . '.pdf', 'I');
    }
}
