<?php

namespace App\Controller;

use App\Repository\BrandSketchRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @IsGranted("ROLE_ADMIN")
 */
class SketchController extends AbstractController
{
    /**
     * @Route("/sketches", name="sketches")
     */
    public function index(BrandSketchRepository $repository)
    {
        $breadcrumbs = ['Sketches'];

        $records = [];
        $sketches = $repository->findBy([],[
            'id' => 'DESC',
        ]);
        foreach($sketches as $sketch){
            $records[] = [
                'sketch_id' => $sketch->getId(),
                'sketch_name' => $sketch->getName(),
                'quote' => '#CBQ00'.$sketch->getQuote()->getId(),
                'quote_id' => $sketch->getQuote()->getId(),
                'customer' => $sketch->getQuote()->getCustomer()->getName().', '.$sketch->getQuote()->getCustomer()->getEmail(),
                'sketch_stamp_type' => $sketch->getstampType()->getValue(),
                'sketch_stamp_shape' => $sketch->getStampShape()->getValue(),
                'sketch_dimension' => $sketch->getDimension(),
                'sketch_weight' => $sketch->getWeight(),
                'sketch_price' => $sketch->getPrice(),
            ];
        }

        return $this->render('sketch/index.html.twig', [
            'title' => $breadcrumbs[0],
            'breadcrumbs' => $breadcrumbs,
            'records' => $records
        ]);
    }
}
