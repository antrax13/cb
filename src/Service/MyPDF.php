<?php
/**
 * Created by PhpStorm.
 * User: peterkosak
 * Date: 11/11/2018
 * Time: 11:52
 */

namespace App\Service;


use jonasarts\Bundle\TCPDFBundle\TCPDF\TCPDF;

class MyPDF extends TCPDF
{
    //Page header
    public function Header() {
        // Logo
        $image_file = 'http://peterkosak.com/cocktailbrandalismlogo.jpg';
//        $image_file = 'http://cocktailbrandalism.com/images/bg1.jpg';
        $this->Image($image_file, 15, 15, 50, '', 'jpg', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $style = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,170,231));

        $this->Line(15, 40, 195, 40, $style);
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', '', 8);
        // Page number

        $this->Cell(0, 10, "CocktailBrandalism.com (c) ".date('Y'), 0, false, 'L', 0, '', 0, false, 'T', 'M');
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}

