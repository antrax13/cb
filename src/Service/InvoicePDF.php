<?php
/**
 * Created by PhpStorm.
 * User: peterkosak
 * Date: 05/01/2019
 * Time: 18:29
 */

namespace App\Service;


use jonasarts\Bundle\TCPDFBundle\TCPDF\TCPDF;

class InvoicePDF extends TCPDF
{
    private $invoiceReference;

    public function setInvoiceReference($invoiceReference): void
    {
        $this->invoiceReference = $invoiceReference;
    }

    public function getInvoiceReference()
    {
        return $this->invoiceReference;
    }





    //Page header
    public function Header() {
        $image_file_ltd = 'http://cocktailbrandalism.com/images/email/scotkaltd.png';
        $html = '<table cellspacing="0" cellpadding="1" border="0">
                    <tr>
                        <td rowspan="3" style="font-size: 20px;">PROFORMA INVOICE<br /></td>
                        <td style="font-size: 20px;">&#32;<br />'.$this->getInvoiceReference().'</td>
                        <td style="text-align: right;"><img width="30" src="'.$image_file_ltd.'"></td>
                    </tr>
                </table><hr />';
        $this->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'top', $autopadding = true);

        // Logo
////        $image_file = 'http://cocktailbrandalism.com/images/bg1.jpg';
//        $this->SetFont('helvetica', 'B', 20);
//        $this->Cell(0, 15, 'PROFORMA INVOICE '.$this->getInvoiceReference(), 0, false, 'L', 0, '', 0, false, 'M', 'M');
//        $this->Image($image_file, 15, 15, 50, '', 'jpg', '', 'T', false, 300, 'R', false, false, 0, false, false, false);
//        $this->Cell(0, 15, $this->getInvoiceReference(), 0, false, 'L', 0, '', 0, false, 'M', 'M');
//        $image_file = K_PATH_IMAGES.'logo_example.jpg';
//        $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
//        // Set font
//        // Title
//        $this->Cell(0, 15, 'PROFORMA INVOICE', 0, false, 'C', 0, '', 0, false, 'M', 'M');
//        $headerData = $this->getHeaderData();
//        $this->SetFont('helvetica', 'B', 10);
//        $this->writeHTML($headerData['string']);

    }




    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', '', 8);
        // Page number

        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}