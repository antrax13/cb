<?php

namespace App\Controller;

use App\Entity\Quote;
use App\Repository\PaymentOptionTextRepository;
use App\Service\MyPDF;
use Doctrine\Common\Persistence\ObjectManager;
use jonasarts\Bundle\TCPDFBundle\TCPDF\TCPDF;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PreviewController extends AbstractController
{
    /**
     * @Route("/quote/{id}/preview-and-generate-pdf", name="quote_preview")
     */
    public function showAction(Quote $quote, PaymentOptionTextRepository $repo){
        if($quote->getIsRemoved()){
            $this->addFlash('danger','Quote has been removed. Contact Administrator to update Quote: '.$quote->getId().' manually.');
            return $this->redirectToRoute('quotes');
        }

        $payment = $repo->find(1);
        $breadcrumbs = ['Quote', '#CBQ00'.$quote->getId(), 'Preview & Generate PDF'];


        return $this->render('quote/preview.html.twig', [
            'title' => $breadcrumbs[2],
            'breadcrumbs' => $breadcrumbs,
            'quote' => $quote,
            'paymentOption' => $payment,
        ]);
    }

    /**
     * @Route("/generate-quote/{id}", name="generate_pdf")
     */
    public function generatePDF(Quote $quote, PaymentOptionTextRepository $repo, ObjectManager $manager)
    {
        $payment = $repo->find(1);

        $quote->setGeneratedAt(new \DateTime('now'));
        $quote->setStatus('PDF Generated');
        $manager->persist($quote);
        $manager->flush();

        $pdf = new MyPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
        $pdf->SetCreator('Peter Kosak');
        $pdf->SetAuthor('Peter Kosak');
        $pdf->SetTitle('Cocktail Brandalism  #CBQ00'.$quote->getId());
        $pdf->SetSubject('Quote');
        $pdf->SetKeywords('Cocktail Brandalism');

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

        $pdfHtml = $this->renderView('partials/_print.html.twig', [
            'quote' => $quote,
            'paymentOption' => $payment,
        ]);

        $html = <<<EOF
$pdfHtml
EOF;

        $pdf->writeHTML($html, true, false, true, false,'');
        $pdf->Output('CocktailBrandalism-#CBQ00'.$quote->getId().'.pdf', 'I');
    }
}
